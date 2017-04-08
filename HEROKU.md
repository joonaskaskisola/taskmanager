Before first deployment
```
heroku addons:create cleardb:ignite
heroku config:set SYMFONY_ENV=prod
heroku buildpacks:add --index 1 heroku/nodejs
```

After deployment

```
heroku run bash
	$ app/console doctrine:schema:update --force
```
