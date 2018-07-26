# @link https://qiita.com/nmatayoshi/items/e7f34e1e220264131934

FROM php:7-fpm-alpine

RUN set -x && \
  apk add --no-cache icu-libs && \
  apk add --no-cache --virtual build-dependencies icu-dev && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} intl && \
  docker-php-ext-install -j${NPROC} pdo_mysql && \
  apk del --no-cache --purge build-dependencies && \
  rm -rf /tmp/pear && \
  apk add --no-cache openssh-client
RUN set -x && \
  deluser www-data && \
  addgroup -g 50 -S www-data && \
  adduser -u 1000 -D -S -G www-data www-data

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin \
    && php -r "unlink('composer-setup.php');"

WORKDIR /work

#CMD ["/bin/sh"]
ENTRYPOINT /bin/sh
