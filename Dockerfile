FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ="America/Monterrey"

WORKDIR /usr/src/app

RUN apt update && apt install -qq --no-install-recommends apt-utils wget git curl cron unzip lsb-release \
    apt-transport-https ca-certificates inotify-tools software-properties-common -y && \
    add-apt-repository ppa:ondrej/php -y && \
    bash -c "apt-get -qq update && apt-get install -qq --no-install-recommends -y \
    php8.1 php8.1-{fpm,bcmath,bz2,intl,gd,mbstring,mysql,zip,dom,xml,curl,soap,imagick,memcached,redis,sqlite}" && \
    apt-get remove --purge -y software-properties-common && \
    apt-get autoremove -y && \
    apt-get clean && \
    apt-get autoclean && \
    echo -n > /var/lib/apt/extended_states && \
    rm -rf /var/lib/apt/lists/* && \
    rm -rf /usr/share/man/?? && \
    rm -rf /usr/share/man/??_*

COPY web/files/custom.user-local.ini /etc/php/8.1/cli/conf.d/custom.user.ini
COPY web/files/custom.user-local.ini /etc/php/8.1/fpm/conf.d/custom.user.ini
COPY web/files/www.conf /etc/php/8.1/fpm/pool.d/www.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sSL https://phar.phpunit.de/phpunit.phar -o /usr/bin/phpunit && chmod +x /usr/bin/phpunit \
    && ln -s /usr/sbin/php-fpm8.1 /usr/sbin/php-fpm \
    && mkdir /usr/src/logs \
    && mkdir /run/php/

EXPOSE 9000

CMD [ "/usr/sbin/php-fpm", "-F" ]