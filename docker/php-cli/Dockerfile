FROM mpwar2021/php-fpm:8.0.3

ARG XDEBUG_VERSION="2.8.0"
RUN apt-get update -y \
    && apt-get install -y \
        bash \
        zsh \
        git \
        make \
        iputils-ping \
        default-mysql-client \
        nano \
        tig \
        gnupg \
        nodejs \
        neofetch \
        httpie \
        tig \
        nyancat \
    && apt-get clean

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update -y \
    && apt-get install -y --no-install-recommends yarn \
    && apt-get clean

ARG COMPOSER_VERSION="2.0.11"
RUN wget -O /usr/bin/composer https://getcomposer.org/download/${COMPOSER_VERSION}/composer.phar \
    && chmod +x /usr/bin/composer

RUN sh -c "$(wget -O- https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

ADD rootfs /

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["php"]
