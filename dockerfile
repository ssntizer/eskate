# Usa una imagen base de PHP 8.1 con Apache
FROM php:8.1-apache

# Instala las extensiones necesarias de PHP para CodeIgniter y PostgreSQL
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-install intl pdo pdo_pgsql

# Copia el contenido de tu proyecto al contenedor
COPY . /var/www/html

# Cambia los permisos del directorio writable
RUN chown -R www-data:www-data /var/www/html/writable

# Configura Apache para apuntar al directorio public
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf

# Expon el puerto 80 para el servidor web
EXPOSE 80
