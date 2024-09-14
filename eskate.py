import urequests as requests  # Importar la biblioteca urequests para MicroPython
import time
import network  # Importar la biblioteca para la conexión Wi-Fi
import dht  # Importar la biblioteca DHT para el sensor
from machine import Pin  # Importar la clase Pin para la configuración de pines

# Credenciales del servidor
SERVER_URL = "http://192.168.118.24:80/eskate/public/index.php/skate/update"
CHIP_ID = "YYYYY1"

# Cambia estos valores por tu red Wi-Fi
SSID = "Redmi Note 12S"  # Nombre de la red Wi-Fi
PASSWORD = "Erie1311"  # Contraseña de la red Wi-Fi

# Configuración del sensor DHT22
DHT_PIN = 4  # Pin donde está conectado el sensor DHT22
sensor = dht.DHT22(Pin(DHT_PIN))  # Inicializa el sensor DHT22

def connect_to_wifi():
    # Crea una instancia de la interfaz Wi-Fi
    wlan = network.WLAN(network.STA_IF)
    wlan.active(True)  # Activa la interfaz Wi-Fi
    
    print("Conectando a Wi-Fi...")
    wlan.connect(SSID, PASSWORD)  # Conéctate a la red Wi-Fi

    # Establece un timeout de conexión
    timeout = 15  # Tiempo máximo de espera en segundos
    start_time = time.time()  # Tiempo de inicio
    
    # Espera hasta que esté conectado o se alcance el timeout
    while not wlan.isconnected():
        if time.time() - start_time > timeout:
            print("Timeout: No se pudo conectar a Wi-Fi")
            return False  # Sale de la función si se alcanza el timeout
        time.sleep(1)  # Espera 1 segundo entre intentos

    print("Conectado a Wi-Fi")
    print("Dirección IP:", wlan.ifconfig()[0])  # Muestra la dirección IP obtenida
    return True

def send_data(codigo, velocidad, bateria, temperatura, id_ubicacion):
    # Datos que vamos a enviar
    data = {
        'api_key': codigo,
        'codigo': codigo,
        'velocidad': velocidad,
        'bateria': bateria,
        'temperatura': temperatura,
        'ID_ubicacion': id_ubicacion
    }

    print("Datos a enviar:", data)
    
    try:
        # Intenta enviar la solicitud POST con los datos en formato JSON
        print(f"Enviando datos a {SERVER_URL}")
        headers = {'Content-Type': 'application/json'}  # Cabecera para enviar JSON
        response = requests.post(SERVER_URL, json=data, headers=headers)
        print(f"Estado de la respuesta: {response.status_code}")  # Imprimir estado de respuesta

        # Verifica la respuesta del servidor
        if response.status_code == 200:
            print("Datos enviados correctamente")
        else:
            print(f"Error al enviar datos: Código de estado {response.status_code}")
            print("Respuesta del servidor:", response.text)
        
        response.close()  # Cierra la conexión de la respuesta
        print("Conexión cerrada correctamente.")

    except OSError as e:
        print(f"Error de conexión OSError en el bloque try: {e}")

def update_skate_data():
    # Lee los datos del sensor DHT22
    try:
        sensor.measure()  # Toma una medida del sensor
        temperatura = sensor.temperature()  # Obtiene la temperatura
        humedad = sensor.humidity()  # Obtiene la humedad (puedes usarlo si lo deseas)
        print(f"Temperatura: {temperatura}°C, Humedad: {humedad}%")

        # Puedes definir el resto de los datos como quieras
        velocidad = 90  # Aquí podrías leer la velocidad desde otro sensor
        bateria = 80    # Aquí podrías leer el nivel de batería desde otro sensor
        ID_ubicacion = 1 # ID de ubicación (puedes cambiarlo según sea necesario)
        
        print("Actualizando datos del skate...")
        
        # Envía los datos
        send_data(CHIP_ID, velocidad, bateria, temperatura, ID_ubicacion)

    except OSError as e:
        print(f"Error al leer el sensor DHT22: {e}")

if __name__ == "__main__":
    print("Iniciando proceso de conexión y actualización de datos...")
    if connect_to_wifi():  # Conectar a la red Wi-Fi
        while True:  # Bucle infinito para enviar datos constantemente
            update_skate_data()  # Actualizar los datos del skate
            time.sleep(5)  # Espera 5 segundos antes de la siguiente actualización
    else:
        print("No se pudo establecer la conexión Wi-Fi.")