ARG RR_VERSION
ARG RR_IMAGE=spiralscout/roadrunner:${RR_VERSION}
ARG PHP_IMAGE_VERSION
ARG PHP_IMAGE=php:${PHP_IMAGE_VERSION}

FROM ${RR_IMAGE} as rr

FROM ${PHP_IMAGE}

RUN apk update && apk add --no-cache \
  vim \
  libzip-dev \
  unzip \
  mycli \
  autoconf \
  make \
  gcc \
  g++ \
  musl-dev \
  php8-dev \
  php8-pear \
  bash

# Install PHP Extensions
RUN docker-php-ext-install zip \
  && docker-php-ext-install sockets \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

# Database drivers
RUN docker-php-ext-install pdo_mysql

# Protobuf
ENV PROTOBUF_VERSION "3.21.4"
RUN pecl channel-update pecl.php.net
RUN pecl install protobuf-${PROTOBUF_VERSION} && docker-php-ext-enable protobuf

# GRPC
RUN apk add linux-headers
RUN MAKEFLAGS="-j 16" pecl install grpc && docker-php-ext-enable grpc

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy RoadRunner
COPY --from=rr /usr/bin/rr /usr/bin/rr

# Install Temporal CLI
COPY --from=temporalio/admin-tools /usr/local/bin/tctl /usr/local/bin/tctl

COPY wait-for-temporal-and-db.sh /usr/local/bin
RUN chmod +x /usr/local/bin/wait-for-temporal-and-db.sh

CMD ["/usr/bin/rr", "serve", "-c", "/app/.rr.yaml"]
