#!/bin/bash
set -e

cd ~/domains/nestqr.com/app

git pull origin main

composer install --no-dev --optimize-autoloader

npm ci
npm run build

/home/treyb/bin/php artisan down

/home/treyb/bin/php artisan migrate --force

/home/treyb/bin/php artisan cache:clear
/home/treyb/bin/php artisan config:clear
/home/treyb/bin/php artisan route:clear
/home/treyb/bin/php artisan view:clear
/home/treyb/bin/php artisan event:clear

/home/treyb/bin/php artisan config:cache
/home/treyb/bin/php artisan route:cache
/home/treyb/bin/php artisan view:cache
/home/treyb/bin/php artisan event:cache

/home/treyb/bin/php artisan optimize

/home/treyb/bin/php artisan queue:restart

/home/treyb/bin/php artisan up

sudo supervisorctl restart all
sudo systemctl restart php8.3-fpm
sudo systemctl reload apache2
