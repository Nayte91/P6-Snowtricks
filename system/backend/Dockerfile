FROM php:7-fpm-alpine

RUN apk add --no-cache --update --virtual buildDeps build-base autoconf \
     && pecl install xdebug > /dev/null \
     && apk del buildDeps build-base autoconf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app