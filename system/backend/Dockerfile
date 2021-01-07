FROM php:7-fpm-alpine

RUN apk add --no-cache --update --virtual buildDeps build-base autoconf \
    && apk add libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install xdebug > /dev/null \
    && apk del buildDeps build-base autoconf

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

WORKDIR /app