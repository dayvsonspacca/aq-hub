FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app
ENTRYPOINT ["php"]