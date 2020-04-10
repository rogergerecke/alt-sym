FROM php:7.4-apache

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Install useful tools
RUN apt-get -y install apt-utils nano wget dialog


# Install important libraries
RUN apt-get -y install --fix-missing apt-utils build-essential git curl libcurl4-openssl-dev libonig-dev zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug-2.9.2
RUN docker-php-ext-enable xdebug

# Other PHP7 Extensions
RUN apt-get -y install libmcrypt-dev

RUN pecl install mcrypt-1.0.3
RUN docker-php-ext-enable mcrypt

RUN rm /etc/apt/preferences.d/no-debian-php

# Download packages
RUN apt-get -y install \
    libsqlite3-dev \
    libsqlite3-0 \
    mariadb-client \
    libxml2-dev \
    zlib1g-dev \
    zlib1g-dev \
    libzip-dev \
    php-soap \
    libyaml-dev

RUN docker-php-ext-install \
    pdo_mysql \
    pdo_sqlite \
    mysqli \
    curl \
    tokenizer \
    json \
    soap \
    zip

# Install YAML extension
RUN pecl install yaml-2.0.4 and  echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini

# unicode symbols activation
RUN apt-get -y install libicu-dev
RUN docker-php-ext-install -j$(nproc) intl

# GD Libary install
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

# todo add ssl cert creation

# a2enmod: enable apache modules and start it rewrite, headers, ssl
RUN a2enmod rewrite headers
