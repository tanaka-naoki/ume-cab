FROM php:7.0-apache
ADD . /var/www/html
WORKDIR /var/www/html
RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng12-dev libmcrypt-dev git vim wget \
  && apt-get install -y mecab libmecab-dev mecab-ipadic-utf8 git make curl xz-utils re2c \
  && mkdir /usr/lib/mecab/library \
  && cd /usr/lib/mecab/library \
  && git clone --depth 1 https://github.com/neologd/mecab-ipadic-neologd.git \
  && mkdir /usr/lib/mecab/dic \
  && cd mecab-ipadic-neologd && (echo yes | ./bin/install-mecab-ipadic-neologd) \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv mcrypt

# php-mecab
RUN cd /usr/local/src
RUN wget https://github.com/rsky/php-mecab/archive/v0.6.0.tar.gz && tar xzvf v0.6.0.tar.gz
RUN cd php-mecab-0.6.0/mecab && phpize && ./configure --with-mecab=/usr/bin/mecab-config && make && make install
RUN echo "extension=mecab.so" >> /usr/local/etc/php/conf.d/30-mecab.ini

COPY apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY apache2/sites-available/001-default.conf /etc/apache2/sites-available/
RUN a2ensite 001-default
