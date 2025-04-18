FROM php:8.2-fpm-alpine

RUN apk --update --no-cache add \
        libpq \
        libpng \
        libzip \
        zip \
        unzip \
        git \
        libpq-dev \
        libpng-dev \
        libzip-dev \
        libexif \
        libxml2-dev \
        libexif-dev \
        icu-dev \
        autoconf \
        g++ \
        make && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip && \
    docker-php-ext-configure exif && \
    docker-php-ext-install exif && \
    docker-php-ext-install soap && \
    docker-php-ext-install pdo pdo_pgsql && \
    docker-php-ext-install intl && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www
