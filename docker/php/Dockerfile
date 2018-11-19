FROM php:7.2-cli

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
&& php -r "unlink('composer-setup.php');"

RUN apt-get update && apt-get install -y \
git \
zlib1g-dev \
libgmp-dev

# install php extensions
RUN docker-php-ext-install \
zip \
gmp