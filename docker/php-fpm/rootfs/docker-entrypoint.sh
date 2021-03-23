#!/bin/bash
set -e

if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

# Replace xdebug template
envsubst < /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini.tpl > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

exec "$@"
