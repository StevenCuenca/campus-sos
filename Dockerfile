
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    curl git unzip ca-certificates \
    libpq-dev libzip-dev libicu-dev libonig-dev \
    libxml2-dev libcurl4-openssl-dev \
    && docker-php-ext-install \
    pdo_pgsql pgsql pdo_mysql zip intl mbstring \
    bcmath pcntl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .

RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN composer install --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

RUN npm ci && npm run build

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
