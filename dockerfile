# Usa una imagen base de PHP 8.1 con Apache
FROM php:8.1-apache

# Instala las extensiones necesarias de PHP para CodeIgniter
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia el contenido de la carpeta public de tu proyecto a la carpeta root de Apache
COPY ./public /var/www/html

# Copia el resto de tu proyecto al contenedor
COPY . /app

# Configura Apache para apuntar al directorio public
RUN echo "DocumentRoot /var/www/html" > /etc/apache2/sites-available/000-default.conf

# Expon el puerto 80 para el servidor web
EXPOSE 80
