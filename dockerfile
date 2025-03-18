# Usa una imagen base de PHP 8.1 con Apache
FROM php:8.1-apache

# Instala las dependencias necesarias para MySQL, oniguruma, cURL, y Composer
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    libonig-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install intl mysqli pdo pdo_mysql mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia el contenido de tu proyecto al contenedor
COPY . /var/www/html

# Cambia los permisos del directorio writable
RUN chown -R www-data:www-data /var/www/html/writable

# Configura Apache para apuntar al directorio public
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite para URLs amigables
RUN a2enmod rewrite

# Expon el puerto 80 para el servidor web
EXPOSE 80

# Establecer las variables de entorno (esto es opcional y depende de tu configuración)
ENV PAYPAL_CLIENT_ID="AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ"
ENV PAYPAL_SECRET="ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A"
