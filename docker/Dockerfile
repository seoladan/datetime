ARG PHP_VERSION=8.2
ARG PHP_VARIANT=cli

FROM php:${PHP_VERSION}-${PHP_VARIANT} AS base


FROM base AS install

COPY --from=ghcr.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

RUN apt-get update && apt-get install -y git unzip


FROM install AS dev

RUN install-php-extensions xdebug

WORKDIR /var/www/html
