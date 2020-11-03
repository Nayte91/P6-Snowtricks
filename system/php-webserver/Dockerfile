ARG PHP_VERSION

FROM php:${PHP_VERSION}-apache

LABEL maintainer="Nayte"

ARG APP_ENV

COPY apache.conf /etc/apache2/sites-available/000-default.conf
# Install selected extensions and other stuff
RUN if [ ${APP_ENV} = "dev" ]; then pecl install xdebug >/dev/null ; docker-php-ext-enable xdebug >/dev/null ; fi