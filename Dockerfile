# @link https://qiita.com/nmatayoshi/items/e7f34e1e220264131934

FROM php:7.1-alpine

LABEL  maintainer "kojiro <kojiro@ryusei-sha.com>"

RUN set -x && \
  apk add --no-cache icu-libs && \
  apk add --no-cache --virtual build-dependencies icu-dev && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} pdo_mysql && \
  apk del --no-cache --purge build-dependencies && \
  rm -rf /tmp/pear

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin \
    && php -r "unlink('composer-setup.php');"

# Set a path to command.
ENV PATH $PATH:/root/.composer/vendor/bin

# Install php-dbdoc
RUN composer.phar global require kojiro526/php-dbdoc

WORKDIR /work
