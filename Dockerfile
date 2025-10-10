FROM php:8.3-fpm AS base

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libpq-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

FROM base

COPY . /var/www

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www \
    && find /var/www -type d -exec chmod 755 {} \; \
    && find /var/www -type f -exec chmod 644 {} \; \
    && chmod -R 775 /var/www/cache /var/www/logs

EXPOSE 9000

USER www-data

CMD ["php-fpm"]