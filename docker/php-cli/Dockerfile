FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    libonig-dev \
    git \
    libz-dev \
    libtidy-dev \
    iputils-ping \
    libicu-dev \
    libmemcached-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libxml2-dev \
    libbz2-dev \
    libjpeg62-turbo-dev \
    librabbitmq-dev \
    libzip-dev \
    curl

RUN docker-php-ext-configure bcmath --enable-bcmath \
  && docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-configure pdo_mysql --with-pdo-mysql \
  && docker-php-ext-configure mbstring --enable-mbstring \
  && docker-php-ext-configure soap --enable-soap \
  && docker-php-ext-install tidy pdo pdo_mysql mysqli zip bcmath mbstring pcntl soap sockets

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP Configuration required for PHP SDK
RUN touch /usr/local/etc/php/conf.d/memory.ini \
    && echo "memory_limit = 2048M;" >> /usr/local/etc/php/conf.d/memory.ini

RUN touch /usr/local/etc/php/conf.d/phar.ini \
    && echo "phar.readonly = Off;" >> /usr/local/etc/php/conf.d/phar.ini

RUN touch /usr/local/etc/php/conf.d/timezone.ini \
    && echo "date.timezone ='Europe/Moscow'" >> /usr/local/etc/php/conf.d/timezone.ini

RUN cd / && \
    git clone https://github.com/aws/aws-sdk-php.git

RUN cd /aws-sdk-php && \
    composer install && \
    make build && \
    make test