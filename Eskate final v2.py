import time
import machine # Importa el módulo machine completo para usar machine.reset()
import json
import random
import urequests
import dht      # Biblioteca para el sensor DHT
import micropyGPS # Asegúrate de que el archivo se llama micropyGPS.py

# --- Configuración UART para el SIM800L (Módulo GSM) ---
UART_GSM_ID = 2
UART_GSM_TX = 26 # Pin TX del ESP32 conectado al RX del SIM800L
UART_GSM_RX = 25 # Pin RX del ESP32 conectado al RX del SIM800L
UART_GSM_BAUDRATE = 9600

uart_gsm = None
rtc = machine.RTC() # Inicializa el objeto RTC del ESP32 globalmente

# --- Configuración del sensor DHT22 ---
DHT_PIN = 21   # Pin donde está conectado el sensor DHT22
sensor_dht = dht.DHT22(machine.Pin(DHT_PIN)) # Inicializa el sensor DHT22

# --- Configuración del módulo GPS ---
GPS_UART_ID = 1
GPS_UART_TX = 19
GPS_UART_RX = 18
GPS_UART_BAUDRATE = 9600

gps_uart = machine.UART(GPS_UART_ID, baudrate=GPS_UART_BAUDRATE, tx=machine.Pin(GPS_UART_TX), rx=machine.Pin(GPS_UART_RX))
gps = micropyGPS.MicropyGPS()

# --- Configuración del servidor y clave API ---
SERVER_URL = "https://eskate.onrender.com/index.php/skate/update"
API_KEY = "YYYYY1" # Clave API que se verifica en el servidor
APN = "datos.personal.com" # APN de tu proveedor de servicios móviles

# Variable global para el Watchdog Timer
wdt = None  

# Mensajes no solicitados comunes que queremos ignorar (en bytes)
# Esta lista es solo para referencia o depuración, no se usa para filtrar en _read_uart_response
unsolicited_messages_bytes = [
    b'Call Ready\r\n', 
    b'SMS Ready\r\n', 
    b'+CPIN: READY\r\n', 
    b'OK\r\n', 
    b'ERROR\r\n' 
]

# --- Funciones de Utilidad GSM ---

def init_uart_gsm():
    global uart_gsm
    try:
        print("Iniciando UART para GSM...")
        uart_gsm = machine.UART(UART_GSM_ID, baudrate=UART_GSM_BAUDRATE, tx=machine.Pin(UART_GSM_TX), rx=machine.Pin(UART_GSM_RX))
        print("UART para GSM inicializada en U{}.".format(UART_GSM_ID))
        return True
    except Exception as e:
        print("Error al inicializar UART para GSM: {}".format(e))
        return False

# Nueva función para dormir y alimentar el Watchdog
def feed_wdt_sleep(duration_seconds):
    global wdt
    start_time = time.ticks_ms()
    end_time = start_time + duration_seconds * 1000
    while time.ticks_ms() < end_time:
        if wdt:
            wdt.feed()
        time.sleep_ms(100) # Alimentar WDT cada 100ms

def _read_uart_response(timeout=1000, expected_end=None):
    global wdt # Accede a la variable global wdt
    start_time = time.ticks_ms()
    response_buffer = b'' # Usamos un buffer para acumular bytes
    last_feed_time = time.ticks_ms() # Para controlar cuándo se alimentó por última vez
   
    while time.ticks_diff(time.ticks_ms(), start_time) < timeout:
        # Alimenta el Watchdog periódicamente para evitar reinicios
        if wdt and time.ticks_diff(time.ticks_ms(), last_feed_time) > 1000: 
            wdt.feed()
            last_feed_time = time.ticks_ms()

        if uart_gsm and uart_gsm.any(): 
            read_data = uart_gsm.read()
            if read_data:
                response_buffer += read_data
                
                # Buscar la respuesta esperada en el buffer (en bytes)
                if expected_end and expected_end.encode() in response_buffer:
                    break # Salir del bucle una vez que se encuentra la cadena esperada
                
        feed_wdt_sleep(0.01) # Cede la CPU más frecuentemente y alimenta WDT (10ms)

    # Si se agota el tiempo, devuelve lo que se haya acumulado en el buffer
    try:
        return response_buffer.decode('utf-8', 'ignore').strip()
    except UnicodeError as e:
        print(f"ERROR DECODIFICACIÓN (UnicodeError) en _read_uart_response: {e}. Bytes brutos: {response_buffer}")
        return ""

def send_at_command(command, timeout=1000, expected_response="OK"):
    if not uart_gsm:
        print(f"ERROR: uart_gsm no está inicializado para el comando {command}")
        return None

    # Limpiar el buffer antes de enviar el comando para evitar lecturas antiguas
    if uart_gsm.any():
        uart_gsm.read()
       
    print("[TX] {}".format(command))
    uart_gsm.write(command + '\r\n')
   
    response = _read_uart_response(timeout=timeout, expected_end=expected_response)
    print("[RX] {}".format(response))
   
    # La verificación ahora se hace en _read_uart_response, solo necesitamos que devuelva algo
    if response and expected_response.encode() in response.encode():
        return response
    return None

def full_reset_gsm():
    print("--- Realizando limpieza y reinicio completo del módulo GSM ---")
    send_at_command('AT+CFUN=1,1', timeout=5000)
    feed_wdt_sleep(5) # Usar feed_wdt_sleep

def setup_pdu_mode():
    # Cambiado a modo texto (AT+CMGF=1) que es más común y menos propenso a errores si no se usa PDU.
    print("--- Configurando Modo Texto para SMS (AT+CMGF=1) ---")
    send_at_command('AT+CMGF=1', timeout=1000) # Se cambió a 1 para modo texto

def check_gsm_registration():
    print("--- Verificación inicial del módulo GSM (AT, CREG, CSQ) ---")
   
    # Nuevo: Bucle para asegurar que el módulo responde a AT antes de continuar
    print("Verificando respuesta básica del módulo (AT)...")
    at_ok = False
    for i in range(5):
        if send_at_command('AT', timeout=2000):
            at_ok = True
            print("Módulo GSM responde a AT.")
            break
        print(f"Intento AT {i+1}/5: Módulo no responde. Reintentando...")
        feed_wdt_sleep(2) # Usar feed_wdt_sleep
    if not at_ok:
        print("Error: Módulo GSM no responde a AT después de varios intentos. No se puede continuar.")
        return False

    # Verificar calidad de señal primero
    csq_response = send_at_command('AT+CSQ', timeout=5000, expected_response="+CSQ:") # Aumentado timeout
    if csq_response:
        if csq_response.strip() == '+CSQ:': # Explicit check for empty CSQ response
            print("Advertencia: El módulo responde a AT+CSQ? pero no proporciona valores de señal (solo '+CSQ:'). Esto indica SIN SEÑAL o problema de antena/SIM.")
        else:
            try:
                csq_value = int(csq_response.split('+CSQ: ')[1].split(',')[0])
                print("Calidad de señal: {}".format(csq_value))
                if csq_value == 99: # 99 indica no signal
                    print("Advertencia: No hay señal GSM (CSQ=99). El registro podría fallar.")
                elif csq_value < 10: # Valores bajos, indicando señal débil
                    print("Advertencia: Señal GSM muy débil (CSQ={}). El registro podría ser inestable.".format(csq_value))
            except (IndexError, ValueError):
                print(f"Advertencia: No se pudo parsear la calidad de señal CSQ. Respuesta cruda: '{csq_response}'")
    else:
        print("Advertencia: No se recibió respuesta de AT+CSQ?.")


    print("Forzando Selección de Red Automática (AT+COPS=0)...")
    send_at_command('AT+COPS=0', timeout=10000) # Aumentado timeout a 10 segundos
    feed_wdt_sleep(5) # Usar feed_wdt_sleep # Espera adicional después de COPS para que el módulo inicie el registro

    print("Esperando registro en la red (AT+CREG?)...")
    for i in range(20): # Intentos de registro
        creg_response = send_at_command('AT+CREG?', timeout=5000, expected_response="+CREG:") # Aumentado timeout
       
        if creg_response:
            # Filtrar mensajes no solicitados de la respuesta cruda de CREG para depuración
            filtered_creg_response = creg_response
            for um_str in [um.decode('utf-8', 'ignore').strip() for um in unsolicited_messages_bytes]:
                if um_str in filtered_creg_response:
                    filtered_creg_response = filtered_creg_response.replace(um_str, '')

            if '+CREG: 0,1' in filtered_creg_response or '+CREG: 0,5' in filtered_creg_response:
                print("Módulo GSM registrado en la red.")
                return True
            elif filtered_creg_response.strip() == '+CREG:': # Explicit check for empty CREG response
                print(f"Intento {i+1}/20: Módulo no registrado. Respuesta CREG: '{filtered_creg_response}' (Respuesta vacía, esperando registro). Esto indica SIN SEÑAL o problema de antena/SIM.")
            else:
                print(f"Intento {i+1}/20: Módulo no registrado. Respuesta CREG inesperada: '{filtered_creg_response}'")
        else:
            print(f"Intento {i+1}/20: No se recibió respuesta CREG. Reintentando...")
       
        feed_wdt_sleep(2) # Usar feed_wdt_sleep
    print("Error: No se pudo registrar en la red GSM después de varios intentos.")
    return False

def connect_gprs(apn, user="", password=""):
    print("Iniciando conexión GPRS (AT+CSTT / AT+CIICR / AT+CIFSR)...")
    print("Configurando APN con AT+CSTT: {}".format(apn))
    if not send_at_command('AT+CSTT="{}","{}","{}"'.format(apn, user, password), timeout=10000):
        print("Fallo AT+CSTT")
        return False
    feed_wdt_sleep(2) # Usar feed_wdt_sleep

    print("Activando contexto GPRS con AT+CIICR...")
    if not send_at_command('AT+CIICR', timeout=20000):
        print("Fallo AT+CIICR")
        return False
    feed_wdt_sleep(5) # Usar feed_wdt_sleep

    print("Obteniendo IP local con AT+CIFSR...")
    # Limpiar buffer antes de enviar
    if uart_gsm and uart_gsm.any():
        uart_gsm.read()
    # Asegurarse de que uart_gsm no sea None antes de escribir
    if uart_gsm:
        uart_gsm.write('AT+CIFSR\r\n')
    else:
        print("ERROR: uart_gsm no está inicializado para AT+CIFSR.")
        return False
       
    start_time = time.ticks_ms()
    response_ip_raw = b''
    while time.ticks_diff(time.ticks_ms(), start_time) < 10000:
        # Alimentar el Watchdog periódicamente
        if wdt and time.ticks_diff(time.ticks_ms(), start_time) % 1000 == 0: # Cada segundo
            wdt.feed()

        if uart_gsm and uart_gsm.any():
            read_data = uart_gsm.read()
            if read_data:
                response_ip_raw += read_data
                # Filtrar mensajes no solicitados aquí también
                # NOTA: En esta función, no estamos usando la lista unsolicited_messages_bytes para filtrar.
                # Se mantiene para consistencia, pero la lógica de lectura es más directa.

                decoded_response = response_ip_raw.decode('utf-8', 'ignore')
                if decoded_response.count('.') == 3 and 'OK' not in decoded_response and 'ERROR' not in decoded_response:
                    lines = decoded_response.strip().split('\r\n')
                    ip_address = None
                    for line in lines:
                        if line.count('.') == 3:
                            ip_address = line
                            break
                    if ip_address:
                        print("[RX] {}".format(decoded_response))
                        print("Conexión GPRS establecida. IP: {}".format(ip_address))
                        return True
        feed_wdt_sleep(0.01) # Usar feed_wdt_sleep (10ms)
    print("[RX] {}".format(response_ip_raw.decode('utf-8', 'ignore').strip() if response_ip_raw else 'No IP response'))
    print("Fallo AT+CIFSR: No se obtuvo IP válida.")
    return False

# --- Funciones de Sincronización de Hora (usando GPS o GSM Network Time) ---

def parse_and_set_rtc(cclk_response):
    try:
        # La respuesta esperada es del tipo '+CCLK: "YY/MM/DD,HH:MM:SS+TZ"'
        # Si no contiene comillas, significa que la hora no está presente.
        if '"' not in cclk_response:
            print(f"Error al parsear CCLK: La respuesta no contiene una cadena de tiempo válida. Respuesta: '{cclk_response}'")
            return False
           
        # Extraer la parte entre comillas
        time_string_start_index = cclk_response.find('"') + 1
        time_string_end_index = cclk_response.rfind('"')
           
        if time_string_start_index == 0 or time_string_end_index == -1 or time_string_end_index <= time_string_start_index:
            print(f"Error al parsear CCLK: No se pudo extraer la cadena de tiempo entre comillas. Respuesta: '{cclk_response}'")
            return False
           
        full_time_str = cclk_response[time_string_start_index:time_string_end_index]
        parts = full_time_str.split(',')
           
        if len(parts) < 2: # Asegurarse de que haya al menos fecha y hora
            print(f"Error al parsear CCLK: Formato de tiempo incompleto (falta fecha o hora). Respuesta: '{cclk_response}'")
            return False

        date_str = parts[0]
        # Manejar posibles formatos de zona horaria (+/-) o ausencia
        time_str_with_tz = parts[1]
        time_str = time_str_with_tz.split('+')[0].split('-')[0] 

        year = int(date_str[0:2]) + 2000
        month = int(date_str[3:5])
        day = int(date_str[6:8])
        hour = int(time_str[0:2])
        minute = int(time_str[3:5])
        second = int(time_str[6:8])

        dummy_time_tuple = (year, month, day, hour, minute, second, 0, 0)
        seconds_since_epoch = time.mktime(dummy_time_tuple)
           
        argentina_offset_seconds = -3 * 3600 
        local_time_seconds = seconds_since_epoch + argentina_offset_seconds
           
        local_time_tuple = time.localtime(local_time_seconds)
           
        rtc.datetime((local_time_tuple[0], local_time_tuple[1], local_time_tuple[2],
                      local_time_tuple[6],
                      local_time_tuple[3], local_time_tuple[4], local_time_tuple[5],
                      0))
           
        print("Hora del ESP32 sincronizada con GPRS y ajustada a zona horaria de Argentina: {}".format(time.localtime()))
        return True
    except Exception as e:
        print(f"Error al parsear CCLK o establecer RTC: {e}. Respuesta original: '{cclk_response}'")
        return False

def sync_time_with_gps_or_gsm_network():
    global unsolicited_messages_bytes # Declara la variable global aquí
   
    # --- Intento 1: Sincronizar hora con GPS ---
    print("Iniciando sincronización de hora con GPS...")
    for i in range(10): # Intentar obtener fix GPS por un tiempo
        update_gps()
        # Asegurarse de que gps.timestamp[0] sea un número y no None
        if gps.timestamp and gps.timestamp[0] is not None and gps.timestamp[0] > 0: # Si hay un timestamp válido del GPS (año > 0)
            try:
                # El timestamp del GPS es UTC. Ajustamos a la zona horaria de Argentina.
                # Aseguramos que todas las partes sean enteros
                gps_year = int(gps.date[2]) + 2000 # Año completo
                gps_month = int(gps.date[1])
                gps_day = int(gps.date[0])
                gps_hour = int(gps.timestamp[0])
                gps_minute = int(gps.timestamp[1])
                gps_second = int(gps.timestamp[2])
                   
                utc_time_tuple = (gps_year, gps_month, gps_day, gps_hour, gps_minute, gps_second, 0, 0)
                seconds_since_epoch_utc = time.mktime(utc_time_tuple)
                   
                argentina_offset_seconds = -3 * 3600 
                local_time_seconds = seconds_since_epoch_utc + argentina_offset_seconds
                   
                local_time_tuple = time.localtime(local_time_seconds)
                   
                rtc.datetime((local_time_tuple[0], local_time_tuple[1], local_time_tuple[2],
                              local_time_tuple[6], # weekday
                              local_time_tuple[3], local_time_tuple[4], local_time_tuple[5],
                              0)) # yearday (not used by rtc.datetime)
                   
                print("Hora del ESP32 sincronizada con GPS y ajustada a zona horaria de Argentina: {}".format(time.localtime()))
                return True
            except Exception as e:
                print(f"Error al parsear o establecer RTC con GPS: {e}")
        print(f"Esperando fix GPS para sincronizar hora... Intento {i+1}/10")
        feed_wdt_sleep(5) # Usar feed_wdt_sleep # Espera entre intentos de fix GPS

    print("Advertencia: No se pudo obtener hora del GPS. Intentando con la red GSM...")

    # --- Fallback a AT+CCLK? si GPS falla ---
    print("Iniciando sincronización de hora con la red GSM (AT+CCLK?) como fallback...")
   
    if not uart_gsm:
        print("ERROR: uart_gsm no está inicializado. No se puede sincronizar la hora vía GSM.")
        return False

    # Habilitar el módulo para obtener la hora de la red y guardarlo
    print("Habilitando sincronización de hora de red (AT+CLTS=1)...")
    clts_success = False
    for i in range(3): # Reintentar AT+CLTS=1 hasta 3 veces
        if send_at_command('AT+CLTS=1', timeout=2000):
            # Guardar la configuración para que persista después de un reinicio
            if send_at_command('AT&W', timeout=2000): 
                clts_success = True
                break
        print(f"Advertencia: Fallo al habilitar/guardar AT+CLTS=1. Reintento {i+1}/3.")
        feed_wdt_sleep(2) # Usar feed_wdt_sleep # Pequeña espera antes del siguiente intento
       
    if not clts_success:
        print("Advertencia: No se pudo habilitar AT+CLTS=1 después de varios intentos. La hora de red podría no estar disponible.")
   
    # Dar al módulo más tiempo para adquirir la hora de red después de CLTS
    print("Esperando que el módulo adquiera la hora de red (hasta 30s)...")
    feed_wdt_sleep(30) # Usar feed_wdt_sleep # Tiempo de espera aumentado

    for attempt in range(5): # Intentar CCLK hasta 5 veces para mayor robustez
        print(f"Intento CCLK {attempt + 1}/5 para sincronizar hora...")
       
        # Limpiar buffer antes de enviar comando AT+CCLK?
        if uart_gsm.any():
            uart_gsm.read()
           
        print("[TX] AT+CCLK?")
        uart_gsm.write('AT+CCLK?\r\n')
           
        # Leer respuesta directamente para AT+CCLK?
        response_buffer = b''
        start_time_cclk = time.ticks_ms()
        while time.ticks_diff(time.ticks_ms(), start_time_cclk) < 3000: # Timeout de 3 segundos para CCLK
            if uart_gsm.any():
                read_data = uart_gsm.read()
                if read_data:
                    response_buffer += read_data
                    # Buscar "+CCLK:" y "OK" o "ERROR" para saber que la respuesta ha terminado
                    if b'+CCLK:' in response_buffer and (b'OK\r\n' in response_buffer or b'ERROR\r\n' in response_buffer):
                        break
            feed_wdt_sleep(0.01) # Usar feed_wdt_sleep (10ms)
           
        cclk_raw_response = response_buffer.decode('utf-8', 'ignore').strip()
        print(f"[RX] {cclk_raw_response}")

        # Filtrar mensajes no solicitados de la respuesta cruda
        # NOTA: En esta función, no estamos usando la lista unsolicited_messages_bytes para filtrar.
        # Se mantiene para consistencia, pero la lógica de lectura es más directa.
        filtered_cclk_response = cclk_raw_response
        # for um_str in [um.decode('utf-8', 'ignore').strip() for um in unsolicited_messages_bytes]:
        #   if um_str in filtered_cclk_response:
        #       print(f"[DEBUG] Filtrando mensaje no solicitado en CCLK raw: {um_str}")
        #       filtered_cclk_response = filtered_cclk_response.replace(um_str, '')

        # Ahora, verificar si la respuesta filtrada contiene la cadena de tiempo
        if '+CCLK:' in filtered_cclk_response and '"' in filtered_cclk_response:
            if parse_and_set_rtc(filtered_cclk_response):
                print("Hora sincronizada con AT+CCLK? exitoso.")
                return True # Éxito con CCLK
        else:
            print(f"Advertencia: Respuesta AT+CCLK? no contiene formato de hora esperado. Respuesta filtrada: '{filtered_cclk_response}'")
       
        feed_wdt_sleep(3) # Usar feed_wdt_sleep # Espera un poco más entre intentos de CCLK
       
    print("ERROR: No se pudo sincronizar la hora con AT+CCLK? después de varios intentos.")
    return False # Fallo total en la sincronización de hora

# --- Funciones de Lectura de Sensores y GPS ---

def update_gps():
    while gps_uart.any():
        data = gps_uart.read(1)
        if data:
            gps.update(chr(data[0]))

def valid_gps_data():
    # Asume que un timestamp con año > 0 es válido
    return gps.latitude[0] != 0 and gps.longitude[0] != 0 and gps.latitude[2] != '' and gps.longitude[2] != '' and gps.timestamp[0] > 0

def dms_to_decimal(degrees, minutes, direction):
    decimal = degrees + minutes / 60
    if direction == 'S' or direction == 'W':
        decimal *= -1
    return decimal

def get_gps_coordinates():
    update_gps()
   
    for i in range(30):
        if valid_gps_data():
            lat_degrees = gps.latitude[0]
            lat_minutes = gps.latitude[1]
            lat_direction = gps.latitude[2]
               
            lon_degrees = gps.longitude[0]
            lon_minutes = gps.longitude[1]
            lon_direction = gps.longitude[2]
               
            lat_decimal = dms_to_decimal(lat_degrees, lat_minutes, lat_direction)
            lon_decimal = dms_to_decimal(lon_degrees, lon_minutes, lon_direction)
               
            print(f"GPS Fix. Latitud: {lat_decimal:.5f}, Longitud: {lon_decimal:.5f}")
            return lat_decimal, lon_decimal
        print(f"Esperando fix GPS... Intento {i+1}/30")
        feed_wdt_sleep(1) # Usar feed_wdt_sleep
       
    print("No se pudo obtener fix GPS válido después de varios intentos.")
    return None, None

def get_gps_speed_kmh():
    update_gps()
    if gps.speed[2] is not None:
        speed_knots = gps.speed[2]
        speed_kmh = speed_knots * 1.852
        print(f"Velocidad GPS: {speed_kmh:.2f} km/h")
        return speed_kmh
    else:
        print("Velocidad GPS no disponible.")
        return None

# --- Función Principal para Recolectar y Enviar Datos ---

def collect_and_send_data():
    try:
        sensor_dht.measure()
        temperatura = sensor_dht.temperature()
        print(f"Temperatura: {temperatura}°C")

        bateria = 90
        print(f"Batería: {bateria}%")

        id_ubicacion = 1
        print(f"ID Ubicación: {id_ubicacion}")

        latitud, longitud = get_gps_coordinates()
        if latitud is None or longitud is None:
            print("No se pudieron obtener coordenadas GPS válidas. No se enviarán datos de ubicación.")
            return False

        velocidades = []
        print("Recolectando 5 lecturas de velocidad GPS en 15 segundos...")
        for _ in range(5):
            velocidad = get_gps_speed_kmh()
            if velocidad is not None:
                velocidades.append(velocidad)
            feed_wdt_sleep(3) # Usar feed_wdt_sleep
           
        velocidad_promedio = 0.0
        if len(velocidades) > 0:
            velocidad_promedio = sum(velocidades) / len(velocidades)
            print(f"Velocidades recolectadas: {velocidades}")
            print(f"Velocidad promedio calculada: {velocidad_promedio:.2f} km/h")
            if velocidad_promedio <= 5.0:
                velocidad_promedio = 0.0
        else:
            print("No se pudieron obtener lecturas de velocidad. Velocidad promedio será 0.")

        local_time = time.localtime()
        hora_formateada = "{:02d}:{:02d}:{:02d}".format(local_time[3], local_time[4], local_time[5])
        print(f"Hora actual: {hora_formateada}")

        payload = {
            'api_key': API_KEY,
            'codigo': API_KEY,
            'velocidad': round(velocidad_promedio, 2),
            'bateria': bateria,
            'temperatura': round(temperatura, 2),
            'ID_ubicacion': id_ubicacion,
            'longitud': round(longitud, 5),
            'latitud': round(latitud, 5),
            'hora': hora_formateada
        }

        print("Datos a enviar (payload): {}".format(payload))

        return send_http_post_request(SERVER_URL, payload)

    except OSError as e:
        print(f"Error al leer sensor DHT o GPS: {e}")
        return False
    except Exception as e:
        print(f"Error inesperado al recolectar o preparar datos: {e}")
        return False

def send_http_post_request(url, payload):
    print("Iniciando envío HTTP a: {}".format(url))
    headers = {'Content-Type': 'application/json'}

    try:
        response = urequests.post(url, json=payload, headers=headers, timeout=90)
        print("Respuesta HTTP Status: {}".format(response.status_code))
        print("Respuesta HTTP Body: {}".format(response.text))
        response.close()

        if response.status_code == 200:
            print("Envío de datos exitoso.")
            return True
        else:
            print("Fallo en el envío de datos. Código de estado: {}".format(response.status_code))
            return False
    except Exception as e:
        print("Error durante la petición HTTP con urequests: {}".format(e))
        return False

# --- Bucle Principal del Sistema ---
def main():
    global wdt # Declara wdt como global para poder asignarla
    print("\n--- Iniciando sistema de monitoreo de skate ---")
    
    # Configura el Watchdog Timer global con un timeout amplio
    wdt = machine.WDT(timeout=120000) # Aumentado a 120s para dar tiempo a todas las operaciones

    while True:
        try:
            # Etapa 1: Inicialización y configuración de hardware crítico
            print("\n--- ETAPA 1: HARDWARE E INICIALIZACIÓN ---")
            wdt.feed()
            if not init_uart_gsm():
                print("Fallo CRÍTICO: No se pudo iniciar la comunicación UART GSM.")
                print("Reiniciando ESP32 para intentar recuperar.")
                feed_wdt_sleep(5)
                machine.reset() # Reinicio completo del ESP32

            full_reset_gsm()
            
            # Etapa 2: Esperar SIM Ready y verificar la conectividad del módulo
            print("\n--- ETAPA 2: VERIFICACIÓN DEL MÓDULO GSM Y SIM ---")
            wdt.feed()
            if not check_gsm_registration():
                print("Fallo MODERADO: El módulo no se pudo registrar en la red GSM.")
                print("Reiniciando el ciclo principal para intentarlo de nuevo.")
                feed_wdt_sleep(30)
                continue # Reiniciar el bucle principal desde el principio

            setup_pdu_mode()

            # Etapa 3: Sincronizar la hora para la validación de HTTPS
            print("\n--- ETAPA 3: SINCRONIZACIÓN DE HORA ---")
            wdt.feed()
            if not sync_time_with_gps_or_gsm_network():
                print("Fallo MODERADO: No se pudo sincronizar la hora.")
                print("Reiniciando el ciclo principal. Las peticiones HTTPS podrían fallar sin hora válida.")
                feed_wdt_sleep(15)
                continue # Reiniciar el bucle principal

            # Etapa 4: Conectar a GPRS para tener acceso a internet
            print("\n--- ETAPA 4: CONEXIÓN GPRS ---")
            wdt.feed()
            if not connect_gprs(APN):
                print("Fallo MODERADO: No se pudo establecer la conexión GPRS.")
                print("Reiniciando el ciclo principal para intentar reconectar.")
                feed_wdt_sleep(30)
                continue # Reiniciar el bucle principal

            # Etapa 5: Recolectar y enviar datos (operación principal)
            print("\n--- ETAPA 5: RECOLECCIÓN Y ENVÍO DE DATOS ---")
            wdt.feed()
            if not collect_and_send_data():
                print("Fallo LEVE: No se pudieron recolectar o enviar los datos.")
                print("Intentando de nuevo en el próximo ciclo.")
            else:
                print("Ciclo de datos completado exitosamente.")

            # Espera antes del próximo ciclo
            print("\n--- Esperando 15 segundos para el próximo ciclo ---")
            feed_wdt_sleep(15)

        except Exception as e:
            # Este es el 'reiniciar todo' en caso de un fallo inesperado
            print(f"\n--- ¡ERROR CRÍTICO INESPERADO EN EL CICLO PRINCIPAL! Excepción: {e} ---")
            print("Reiniciando el ESP32 por completo para recuperarse de un fallo grave.")
            feed_wdt_sleep(2)
            machine.reset()

if __name__ == "__main__":
    main()

