FROM php:8.0.9-cli
WORKDIR /usr/src/app
RUN docker-php-ext-install sockets
COPY php.ini /usr/local/etc/php/php.ini
CMD [ "php", "-S", "0.0.0.0:8080", "index.php" ]
EXPOSE 8080/tcp
