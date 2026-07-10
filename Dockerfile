FROM php:8.2-apache

# Install ekstensi sistem yang dibutuhkan Laravel & PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Aktifkan mod_rewrite buat Apache (penting untuk routing Laravel)
RUN a2enmod rewrite

# Set working directory ke folder Apache
WORKDIR /var/www/html

# Salin semua kodingan proyek ke dalam kontainer
COPY . .

# Install Composer secara otomatis
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Ubah permission folder storage dan cache biar bisa ditulis oleh server
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Arahkan Apache DocumentRoot ke folder 'public' milik Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Buka port 80 untuk akses web
EXPOSE 80

CMD php artisan migrate --force && apache2-foreground