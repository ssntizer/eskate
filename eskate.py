import urequests as requests  # Biblioteca para realizar solicitudes HTTP en MicroPython
import time
import network  # Biblioteca para la conexión Wi-Fi
import dht  # Biblioteca para el sensor DHT
from machine import Pin, UART  # Biblioteca para manejar los pines del ESP32
import utime  # Biblioteca para manejar el tiempo
import micropyGPS  # Asegúrate de que el archivo se llama microGPS.py

# Configuración del servidor y clave API
SERVER_URL = "http://192.168.118.24:80/eskate/public/index.php/skate/update"
API_KEY = "YYYYY1"  # Clave API que se verifica en el servidor

# Credenciales de la red Wi-Fi
SSID = "Redmi Note 12S"  # Nombre de la red Wi-Fi
PASSWORD = "Erie1311"  # Contraseña de la red Wi-Fi

# Configuración del sensor DHT22
DHT_PIN = 25   # Pin donde está conectado el sensor DHT22
sensor = dht.DHT22(Pin(DHT_PIN))  # Inicializa el sensor DHT22 en el pin especificado

# Configuración del módulo GPS
GPS_UART = UART(1, baudrate=9600, tx=14, rx=12)  # Ajusta los pines según tu conexión
gps = micropyGPS.MicropyGPS()

# Función para conectarse a la red Wi-Fi
def connect_to_wifi():
    wlan = network.WLAN(network.STA_IF)  # Interfaz de red Wi-Fi
    wlan.active(True)  # Activa la interfaz Wi-Fi
    
    print("Conectando a Wi-Fi...")
    wlan.connect(SSID, PASSWORD)  # Conéctate a la red Wi-Fi

    # Establece un timeout de conexión
    timeout = 15  # Tiempo máximo de espera en segundos
    start_time = time.time()
    
    # Espera hasta que esté conectado o hasta que se agote el tiempo
    while not wlan.isconnected():
        if time.time() - start_time > timeout:
            print("Timeout: No se pudo conectar a Wi-Fi")
            return False  # Devuelve falso si no logra conectarse a tiempo
        time.sleep(1)

    print("Conectado a Wi-Fi")
    print("Dirección IP:", wlan.ifconfig()[0])  # Muestra la dirección IP de la ESP32
    return True

# Función para actualizar los datos del GPS
def update_gps():
    while GPS_UART.any():
        data = GPS_UART.read(1)  # Lee un solo byte (en formato binario)
        if data:  # Asegura que los datos no estén vacíos
            gps.update(chr(data[0]))  # Actualiza la librería con el dato convertido a str

# Verifica si los datos GPS son válidos
def valid_gps_data():
    return gps.latitude[0] != 0 and gps.longitude[0] != 0

# Convierte grados y minutos a grados decimales
def dms_to_decimal(degrees, minutes, direction):
    decimal = degrees + minutes / 60
    if direction == 'S' or direction == 'W':
        decimal *= -1
    return decimal

# Función para enviar los datos al servidor
def send_data(velocidad, bateria, temperatura, id_ubicacion, longitud, latitud, hora):
    # Asegúrate de que todos los campos tienen valores válidos
    if velocidad is None:
        velocidad = 0  # Puedes ajustar este valor según tu lógica

    if bateria is None:
        bateria = 0  # Valor predeterminado para batería si es None

    if temperatura is None:
        temperatura = 0  # Valor predeterminado para temperatura si es None

    if latitud is None or longitud is None:
        print("Error: latitud o longitud son None.")
        return  # No envía datos si falta la posición

    # Datos a enviar en formato JSON
    data = {
        'api_key': API_KEY,
        'codigo': API_KEY,
        'velocidad': velocidad,
        'bateria': bateria,
        'temperatura': temperatura,
        'ID_ubicacion': id_ubicacion,
        'longitud': longitud,
        'latitud': latitud,
        'hora': hora
    }

    print("Datos a enviar:", data)
    
    try:
        headers = {'Content-Type': 'application/json'}
        print(f"Enviando datos a {SERVER_URL}")
        response = requests.post(SERVER_URL, json=data, headers=headers)

        if response.status_code == 200:
            print("Datos enviados correctamente")
        else:
            print(f"Error al enviar datos: Código {response.status_code}")
            print("Respuesta del servidor:", response.text)
        
        response.close()

    except OSError as e:
        print(f"Error de conexión: {e}")

# Función para obtener los datos del GPS
def get_gps_data():
    update_gps()
    
    if valid_gps_data():
        lat_degrees = gps.latitude[0]
        lat_minutes = gps.latitude[1]
        lat_direction = gps.latitude[2]
        
        lon_degrees = gps.longitude[0]
        lon_minutes = gps.longitude[1]
        lon_direction = gps.longitude[2]
        
        lat_decimal = dms_to_decimal(lat_degrees, lat_minutes, lat_direction)
        lon_decimal = dms_to_decimal(lon_degrees, lon_minutes, lon_direction)
        
        print(f"Latitud (decimal): {lat_decimal}")
        print(f"Longitud (decimal): {lon_decimal}")
        
        return lat_decimal, lon_decimal
    else:
        print("Esperando datos GPS...")
        return None, None

# Función para obtener la velocidad del GPS
def get_gps_speed():
    update_gps()
    if gps.speed[2] is not None:  # Verifica que la velocidad no sea nula
        speed_knots = gps.speed[2]  # Velocidad en nudos
        speed_kmh = speed_knots * 1.852  # Convierte a km/h
        print(f"Velocidad: {speed_kmh:.2f} km/h")
        return speed_kmh
    else:
        print("Velocidad GPS no disponible")
        return None

# Función para obtener datos del sensor y enviarlos
def update_skate_data():
    try:
        # Mide la temperatura y la humedad con el sensor DHT22
        sensor.measure()
        temperatura = sensor.temperature()  # Obtiene la temperatura
        bateria = 90  # Valor simulado de batería
        id_ubicacion = 1  # ID de ubicación simulado (debería actualizarse dinámicamente)
        
        # Obtén datos GPS
        latitud, longitud = get_gps_data()
        
        # Lista para almacenar las velocidades
        velocidades = []

        # Obtener 5 velocidades en 15 segundos
        start_time = time.time()
        while len(velocidades) < 5:
            velocidad = get_gps_speed()  # Obtén la velocidad desde el GPS
            if velocidad is not None:
                velocidades.append(velocidad)
            time.sleep(3)  # Espera 3 segundos entre lecturas (3 segundos * 5 lecturas = 15 segundos)

        if latitud is not None and longitud is not None and len(velocidades) == 5:
            # Calcular el promedio de las velocidades
            velocidad_promedio = sum(velocidades) / len(velocidades)
            print(f"Velocidades: {velocidades}")
            print(f"Velocidad promedio: {velocidad_promedio:.2f} km/h")

            # Verifica si el promedio es 5 o menos
            if velocidad_promedio <= 5:
                velocidad_promedio = 0  # Enviar 0 si el promedio es 5 o menos

            # Obtén la hora actual en formato adecuado (HH:MM:SS)
            hora = utime.localtime()  # Obtiene la hora actual
            hora_formateada = f"{hora[3]:02}:{hora[4]:02}:{hora[5]:02}"

            print(f"Temperatura: {temperatura}°C, Batería: {bateria}%, Velocidad promedio: {velocidad_promedio:.2f} km/h, Hora: {hora_formateada}")
            
            # Envía los datos al servidor
            send_data(velocidad_promedio, bateria, temperatura, id_ubicacion, longitud, latitud, hora_formateada)

    except OSError as e:
        print(f"Error al leer el sensor: {e}")

# Programa principal
if __name__ == "__main__":
    if connect_to_wifi():  # Conectar a la red Wi-Fi
        while True:
            update_skate_data()  # Actualiza y envía los datos
            time.sleep(15)  # Espera 15 segundos antes de la siguiente actualización

