FROM php:7-fpm-alpine

RUN apk add --no-cache --update --virtual buildDeps build-base autoconf \
     && pecl install xdebug > /dev/null \
     && apk del buildDeps build-base autoconf

WORKDIR /app