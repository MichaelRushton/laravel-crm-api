FROM php:8.4-fpm-alpine
RUN apk add --no-cache postgresql-dev autoconf g++ linux-headers make && \
    pecl install -f xdebug && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-enable xdebug && \
    apk del --purge autoconf g++ linux-headers make