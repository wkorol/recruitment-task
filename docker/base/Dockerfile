FROM php:8.3-apache-bookworm as recruitment_task

RUN apt-get -y update && apt-get -y upgrade && apt-get -y install wget git bash libpq-dev libzip-dev unzip libxml2-dev
RUN docker-php-ext-install pdo pdo_pgsql
RUN groupadd --gid 1000 recruitment_task \
  && useradd --uid 1000 --gid recruitment_task --shell /bin/bash --create-home recruitment_task

RUN docker-php-ext-install opcache zip soap intl

RUN a2enmod rewrite && a2enmod ssl && a2enmod socache_shmcb

RUN a2ensite 000-default

COPY --from=composer/composer:2.7.7-bin /composer /usr/bin/composer

WORKDIR /home/recruitment_task
