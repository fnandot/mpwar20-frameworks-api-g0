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
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg \
        lsb-release \
    && apt-get clean

RUN  curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg \
    && apt-key add /usr/share/keyrings/docker-archive-keyring.gpg \
    && echo \
         "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian \
         $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null \
    && apt-get update -y \
    && apt-get install -y docker-ce-cli

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

COPY --from=rector/rector /rector /rector
RUN chmod +x /rector/bin/rector \
    && ln -s /rector/bin/rector /usr/bin/rector

RUN mkdir -p /tmp/opcache \
    && /rector/bin/rector list \
    && chmod 777 -R /tmp

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["php"]
