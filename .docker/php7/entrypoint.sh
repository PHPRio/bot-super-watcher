export COMPOSER_ALLOW_SUPERUSER=1
curl https://getcomposer.org/composer.phar --output /var/www/html/composer.phar
php composer.phar global require hirak/prestissimo
php composer.phar install
php-fpm
