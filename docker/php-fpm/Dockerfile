FROM php:7-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    	&& docker-php-ext-configure gd --with-png-dir=/usr/include --with-jpeg-dir=/usr/include \
    	&& docker-php-ext-install gd \
        && docker-php-ext-install pdo_mysql

RUN apt-get update \
  && apt-get install -y libmemcached11 libmemcachedutil2 build-essential libmemcached-dev libz-dev \
  && pecl install memcached \
  && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini \
  && apt-get remove -y build-essential libmemcached-dev libz-dev \
  && apt-get autoremove -y \
  && apt-get clean \
  && rm -rf /tmp/pear

RUN apt-get install -y wget apt-transport-https lsb-release ca-certificates \
  && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
  && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list \
  && apt-get update \
  && apt-get install -y php7.1-cli php7.1-common php7.1-curl php7.1-dev php7.1-gd php7.1-json \
     php7.1-mysql php7.1-readline php7.1-sqlite3 php7.1-xml php7.1-tidy php7.1-bcmath php7.1-bz2 php7.1-fpm \
     php7.1-imap php7.1-intl php7.1-mbstring php7.1-mcrypt php7.1-soap php7.1-xsl php7.1-zip php7.1-memcached

RUN apt-get install -y dnsutils mysql-client

RUN apt-get update && apt-get install -y curl sudo && curl -sL https://deb.nodesource.com/setup_7.x | sudo -E bash - && apt-get install -y nodejs

RUN usermod -u 1000 www-data

ADD start-building.sh .

EXPOSE 9000

CMD ./start-building.sh
