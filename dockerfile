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

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia el contenido del proyecto
COPY . /var/www/html

# Verifica que composer.json existe antes de instalar
RUN ls -lah /var/www/html/

# Forzar reinstalación de dependencias de Composer
RUN rm -rf /var/www/html/vendor /var/www/html/composer.lock
RUN composer install --ignore-platform-reqs --no-dev --prefer-dist --optimize-autoloader

# Cambiar permisos (opcional)
RUN chown -R www-data:www-data /var/www/html/vendor
RUN chmod -R 777 /var/www/html/vendor

# Configura Apache
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Expon el puerto 80
EXPOSE 80