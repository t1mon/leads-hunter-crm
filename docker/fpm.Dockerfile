FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
                                       zlib1g-dev \
                                       libicu-dev g++ \
                                       vim \
                                       libfreetype6-dev \
                                       libjpeg62-turbo-dev \
                                       libmcrypt-dev \
                                       libpng-dev \
                                       zlib1g-dev \
                                       libxml2-dev \
                                       libzip-dev \
                                       libonig-dev \
                                       graphviz \
                                       libcurl4-openssl-dev \
                                       pkg-config \
                                       libpq-dev\
                                       libyaml-dev \
&& docker-php-ext-install pdo pdo_mysql \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl \
&& apt install -y libmagickwand-dev --no-install-recommends \
&& pecl install imagick \
&& docker-php-ext-enable imagick \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) gd \
&& docker-php-ext-install exif

RUN  pecl install yaml && docker-php-ext-enable yaml

WORKDIR /var/www/app

