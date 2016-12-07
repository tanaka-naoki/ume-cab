FROM php:7.0-apache
ADD . /var/www/html
WORKDIR /var/www/html
RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng12-dev libmcrypt-dev git vim　\
  && apt-get install -y mecab libmecab-dev mecab-ipadic-utf8 git make curl xz-utils \
  && cd /tmp
  && git clone --depth 1 https://github.com/neologd/mecab-ipadic-neologd.git \
  && cd /tmp/mecab-ipadic-neologd \
  && mkdir /usr/lib/mecab/dic \
  && ./bin/install-mecab-ipadic-neologd
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv mcrypt
COPY apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY apache2/sites-available/001-default.conf /etc/apache2/sites-available/
RUN a2ensite 001-default
