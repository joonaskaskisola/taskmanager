#!/bin/bash

cd /var/www/symfony/
npm install
./node_modules/webpack/bin/webpack.js --progress --colors
source conf/insert-configs-docker.sh

sleep 2

until mysql -uroot -proot -hmysql  -e ";" ; do
	echo "Waiting for mysql.."
	sleep 2
done

until mysql -uroot -proot -hmysql -e "CREATE DATABASE IF NOT EXISTS taskmanager;" ; do
	echo "Waiting for mysql.."
	sleep 2
done

php7.1 ./composer.phar install
php7.1 app/console run-script build-parameters
php7.1 app/console doctrine:schema:update --force
php7.1 app/console cache:clear --env=prod
rm -rf app/cache/*
