FROM php:8.2-apache

RUN a2enmod rewrite

COPY . /var/www/html/

# aktualizacja i instalacja bibliotek
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        ssh \
        libicu-dev \
        libpq-dev \
        libxslt-dev \
        libxslt-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libc-client-dev \
        libkrb5-dev \
        bash-completion \
        #        locales \
        && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        && docker-php-ext-configure intl \
        && docker-php-ext-configure imap --with-kerberos --with-imap-ssl\
        && docker-php-ext-install \
        pdo_mysql soap opcache calendar zip intl pcntl pdo_pgsql xsl gd bcmath imap


# xdebug
#RUN pecl install xdebug && docker-php-ext-enable xdebug

# docker-php-ext-enable
# https://hub.docker.com/_/php/#how-to-install-more-php-extensions
# pecl install memcached-3.1.4 redis-5.1.1 && docker-php-ext-enable memcached redis

# ENV APACHE_DOCUMENT_ROOT /path/to/new/root

# sciagniecie composera
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# skopiowanie skryptu wait-for-it
COPY docker/build/php/wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it

WORKDIR /var/www/html

# Set the locale
#RUN locale-gen en_US.UTF-8
#ENV LANG en_US.UTF-8
#ENV LANGUAGE en_US:en
#ENV LC_ALL en_US.UTF-8

# wait-for-it czeka na wystartowanie mysql aby zrobic migracje
# apache2-foreground odpala apache
CMD composer install ; wait-for-it mysql:3306 -t 120 -- bin/console doctrine:migrations:migrate -n ; apache2-foreground

# Enable Symfony Console Autocomplete
RUN ./bin/console completion bash > /etc/bash_completion.d/symfony-console
RUN echo "source /etc/bash_completion" >> ~/.bashrc
