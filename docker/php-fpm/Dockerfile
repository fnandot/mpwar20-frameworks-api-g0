FROM php:7.3-fpm

WORKDIR /app

ARG XDEBUG_VERSION="2.8.0"
RUN apt-get update -y \
    && apt-get install -y \
        git \
        gcc \
        g++ \
        openssh-client \
        gettext \
        wget \
        pkg-config \
        librabbitmq-dev \
        unzip \
        iputils-ping \
        zlib1g-dev \
        libicu-dev \
        libxslt-dev \
    && yes '' | pecl install -f xdebug-${XDEBUG_VERSION} \
    && update-ca-certificates \
    && docker-php-ext-install pdo pdo_mysql intl xsl \
    && docker-php-ext-enable xdebug \
    && apt-get clean

ADD rootfs /

RUN chmod +x /docker-entrypoint.sh

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["php-fpm"]
