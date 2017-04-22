#!/bin/bash

export APP_SECRET=ze90gfwy8f7goagyrpow34yh4e6ujh34egs
export BONSAI_URL=https://xj1z5ih8:9xte8ax4a210r34s@ash-2012521.us-east-1.bonsaisearch.net
export CLEARDB_DATABASE_URL=mysql://root:root@$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mysql)/taskmanager?reconnect=true
export MEMCACHEDCLOUD_PASSWORD="root"
export MEMCACHEDCLOUD_SERVERS="$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' memcached):11211"
export MEMCACHEDCLOUD_USERNAME="root"
export MEMCACHIER_PASSWORD="root"
export MEMCACHIER_SERVERS="$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' memcached):11211"
export MEMCACHIER_USERNAME="root"
export RABBITMQ_BIGWIG_REST_API_URL=https://-25RiPr2:nuPmC_0mz11PhW8LuPrvjP1VZLmfoDFY@bigwig.lshift.net/management/125046/api
export RABBITMQ_BIGWIG_RX_URL=amqp://-25RiPr2:nuPmC_0mz11PhW8LuPrvjP1VZLmfoDFY@furry-hazel-1.bigwig.lshift.net:10537/tNwFCnl-j9hw
export RABBITMQ_BIGWIG_TX_URL=amqp://-25RiPr2:nuPmC_0mz11PhW8LuPrvjP1VZLmfoDFY@furry-hazel-1.bigwig.lshift.net:10536/tNwFCnl-j9hw
export RABBITMQ_BIGWIG_URL=amqp://-25RiPr2:nuPmC_0mz11PhW8LuPrvjP1VZLmfoDFY@furry-hazel-1.bigwig.lshift.net:10536/tNwFCnl-j9hw
export CLOUDINARY_URL=cloudinary://115472955743717:ECSOs2Lws8_KobUUuf8o8M5iB0A@hfeaatqa6

export SYMFONY_ENV=prod
