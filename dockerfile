# Usa una imagen base de PHP con Apache
FROM php:7.4-apache

# Copia el contenido de tu aplicación al contenedor
COPY . /var/www/html/

# Instala extensiones de PHP necesarias (opcional)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expon el puerto 80 para el servidor Apache
EXPOSE 80
