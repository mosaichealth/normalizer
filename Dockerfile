FROM php:cli-bullseye

RUN apt-get update -q && apt-get -y upgrade \
&& pecl install xdebug \
&& docker-php-ext-enable xdebug \
&& echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini \
&& echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
&& echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/xdebug.ini \
&& echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
&& echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/xdebug.ini \
&& rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /data
USER www-data

RUN set -eux; \
    if [ -f composer.json ]; then \
		composer install --no-progress; \
    fi
COPY composer.* ./

RUN pwd
RUN ls -lah





