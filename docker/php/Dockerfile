FROM php:7.4-fpm-buster

ARG TIMEZONE

COPY docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update \
    && apt-get install -y --no-install-recommends vim curl debconf subversion git apt-transport-https apt-utils \
    build-essential locales acl mailutils wget zip unzip \
    gnupg gnupg1 gnupg2 zlib1g-dev \
    librabbitmq-dev \
    libssh-dev \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN apt-get update && apt-get install -y zlib1g-dev \
    && docker-php-ext-install zip

RUN docker-php-ext-configure gd --with-jpeg --with-freetype 

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring sockets

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/symfony

EXPOSE 8000

RUN curl -sS https://get.symfony.com/cli/installer | bash \
&& mv /root/.symfony/bin/symfony /usr/local/bin/symfony

#COPY ./entrypoint.sh ./entrypoint.sh
#
#COPY ./etc/wait-for-it.sh ./etc/wait-for-it.sh
#
#RUN chmod +x /entrypoint.sh
#
#RUN chmod +x /etc/wait-for-it.sh