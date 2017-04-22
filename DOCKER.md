# Using docker

@todo: webpack installer

```bash
docker-compose up
source conf/insert-confgs-docker.sh

add-apt-repository ppa:ondrej/php -y
apt-get update
apt-get install -y composer php7.1-{cli,common,curl,dev,gd,json,mysql,readline,sqlite3,xml,tidy,bcmath,bz2,fpm,imap,intl,mbstring,mcrypt,soap,xsl,zip,memcached}
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console cache:clear
```
