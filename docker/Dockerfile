FROM php:8.4-fpm-alpine
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apk add --no-cache postgresql-dev && \
    docker-php-ext-install pdo_pgsql