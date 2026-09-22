# 🛹 Eskate - Sistema IoT de Telemetría y Seguridad

Eskate es una plataforma integral de hardware y software diseñada para el rastreo, telemetría y seguridad de vehículos ligeros (skates/longboards). Este proyecto abarca desde la programación a bajo nivel de microcontroladores hasta el desarrollo de una aplicación web completa con pasarela de pagos.

## 🚀 Sobre el Proyecto

Este sistema fue desarrollado de forma independiente como proyecto de demostración técnica para aplicar a pasantías y posiciones de desarrollo de software (dado que, al cursar el primer año de Ingeniería en Computación, las pasantías universitarias aún no están habilitadas). 

El objetivo principal fue resolver un problema real de hardware y conectividad, construyendo una arquitectura robusta capaz de procesar datos en tiempo real y exponerlos a los usuarios de forma segura.

## 🛠️ Stack Tecnológico

**Hardware & IoT:**
* **Microcontrolador:** ESP32 (Programado en C++ / MicroPython)
* **Conectividad:** Redes móviles 4G LTE
* **Protocolo de Transmisión:** MQTT (Migrado desde HTTPS para optimizar la latencia y el consumo de datos en IoT).

**Backend & Web:**
* **Framework:** CodeIgniter 4 (PHP)
* **Base de Datos:** MySQL / MariaDB (Estructura relacional para Usuarios, Skates, Tracking, Direcciones y Pagos).
* **Integraciones:** API de PayPal para el módulo de compras.

## ⚙️ Arquitectura y Funcionalidades Principales

1. **Telemetría en Tiempo Real (`SkateTrackingModel`):** El ESP32 captura datos geográficos (Latitud, Longitud), métricas de rendimiento (Velocidad, Batería, Temperatura) y los transmite vía MQTT. El backend recibe, filtra y almacena estos datos eliminando registros obsoletos automáticamente para optimizar el almacenamiento.
2. **Sistema de Autenticación y Seguridad (`UserModel`):** Control de acceso estricto con encriptación de contraseñas (`password_hash`), tokens de verificación y recuperación de cuenta por email.
3. **Gestión de Dispositivos (`SkateModel`):** Lógica de vinculación única (un skate solo puede pertenecer a un usuario), asignación de apodos y desvinculación de hardware.
4. **Módulo E-Commerce y Logística:** 
   * Plataforma de venta integrada con **PayPal** (`CompraModel`).
   * Gestión de direcciones de envío (`DireccionModel`) estructurada por Provincias y Localidades.
   * Seguimiento de entregas (`EntregaModel`).

## 🧠 Desafíos Técnicos Resueltos

El desafío más complejo del proyecto fue lograr la estabilidad en la transmisión de datos del hardware al servidor a través de redes móviles. 

Inicialmente, el sistema utilizaba Webhooks (HTTPS), pero presentaba alta latencia y pérdida de paquetes debido a la inestabilidad de las redes celulares en movimiento. La solución fue **reescribir la capa de comunicación del ESP32 para utilizar el protocolo MQTT**, logrando una arquitectura orientada a eventos mucho más ligera, rápida y confiable para un entorno de Internet de las Cosas (IoT).

## 📂 Estructura de la Base de Datos
El proyecto cuenta con un esquema relacional diseñado para escalabilidad:
* `users` / `direccion` / `localidades` / `provincias`: Gestión integral de clientes y logística.
* `skate` / `skate_tracking`: Core del sistema IoT para la última ubicación y el historial de recorrido.
* `pagos` / `entrega`: Registro de transacciones financieras y estado logístico.

---
*Desarrollado con pasión por la ingeniería, la resolución de problemas y el código limpio.*
