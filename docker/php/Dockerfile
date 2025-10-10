FROM php:8.3-fpm

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
    wget

RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

CMD ["php-fpm"]