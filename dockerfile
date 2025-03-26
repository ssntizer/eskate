# Usa PHP 8.1 con Apache
FROM php:8.1-apache

# Instalar dependencias necesarias
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

# Verificar que Composer funciona
RUN composer --version

# Copiar los archivos del proyecto
COPY . /var/www/html

# Verificar que composer.json existe
RUN ls -lah /var/www/html/

# Instalar dependencias de Composer con más verbosidad
RUN composer install --ignore-platform-reqs --no-dev --prefer-dist --optimize-autoloader --verbose || cat /var/www/html/composer.lock

# Cambiar permisos
RUN chown -R www-data:www-data /var/www/html/vendor
RUN chmod -R 777 /var/www/html/vendor

# Configurar Apache
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Exponer puerto 80
EXPOSE 80