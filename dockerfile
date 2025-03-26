# Usa una imagen base de PHP 8.1 con Apache
FROM php:8.1-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    libonig-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install intl mysqli pdo pdo_mysql mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer manualmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia el contenido de tu proyecto al contenedor
COPY . /var/www/html

# Establece permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Cambia al directorio de trabajo
WORKDIR /var/www/html

# Muestra el contenido del directorio para depuración
RUN ls -lah

# Fuerza la instalación de Composer
RUN composer install --ignore-platform-reqs --no-dev --prefer-dist --optimize-autoloader

# Configura Apache para apuntar al directorio public
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite para URLs amigables
RUN a2enmod rewrite

# Expon el puerto 80 para el servidor web
EXPOSE 80

# Establecer variables de entorno
ENV PAYPAL_CLIENT_ID="AdGS2GrGBbZXq41yYDW2A-0dVD5avVuWiQO-XQDVAOxMepuO0HmkCL6kFfwIbLLjIc0gT9tB3KmIL0hJ"
ENV PAYPAL_SECRET="ENwZmSdEKvlXWlybPNngQbhf1KZhN9S_1bVV3lfJbtTFV1oc0waa3RxmYjImQaeeafjMKQe48pbJM07A"

# Comando de inicio del contenedor
CMD ["apache2-foreground"]