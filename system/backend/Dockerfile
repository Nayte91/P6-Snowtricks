ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer_stage

FROM php:${PHP_VERSION}-fpm-alpine

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

RUN echo -e 'Building PHP extensions... \n'   \
    && apk add --no-cache --update --virtual buildDeps build-base autoconf; apk add libzip-dev \
    && docker-php-ext-configure zip; docker-php-ext-install zip \
    && pecl install xdebug > /dev/null \
    && apk del buildDeps build-base autoconf \
 && echo -e '...PHP Extensions OK \n'

WORKDIR /app