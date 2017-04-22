# Using docker

```bash
docker-compose up
source conf/insert-confgs-docker.sh
composer run-script build-parameters
app/console doctrine:database:create
app/console doctrine:schema:update --force
```
