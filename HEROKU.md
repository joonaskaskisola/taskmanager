Before first deployment
```
heroku addons:create cleardb:ignite
heroku buildpacks:set heroku/php
heroku buildpacks:add --index 1 heroku/nodejs
heroku config:set SYMFONY_ENV=prod
```

After deployment

```
heroku run bash
	$ app/console doctrine:schema:update --force
```