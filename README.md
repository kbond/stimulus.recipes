# stimulus.recipes Website

## Local Development

```bash
# install dependencies
composer install

# start local server
symfony server:start -d # auto-starts tailwind build --watch

# view server/tailwind build logs
symfony server:log

# run test suite
vendor/bin/phpunit

# run php-cs-fixer
vendor/bin/php-cs-fixer fix --verbose

# run phpstan
vendor/bin/phpstan
```
