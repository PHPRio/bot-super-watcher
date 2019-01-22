FROM php:7.3-fpm

RUN apt-get update

# Install Postgre PDO
RUN apt-get install -y git libpq-dev libmemcached-dev zlib1g-dev \
    && docker-php-ext-configure mysqli -with-mysqli=mysqlnd \
    && docker-php-ext-configure pdo_mysql -with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure pgsql \
    && docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql

RUN pecl install xdebug-2.7.0beta1
RUN docker-php-ext-enable xdebug
RUN pecl install memcached
RUN docker-php-ext-enable memcached

COPY config/php.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www/html

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
