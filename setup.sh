#!/usr/bin/env sh
echo 'Setting you up for running the project with Sail'

composer i --no-interaction
bun i --silent && bun run build
if [ ! -f '.env' ] && cp .env.example .env
php artisan key:generate

echo 'max_execution_time = 600' >> vendor/laravel/sail/runtimes/8.5/php.ini

vendor/bin/sail build

echo 'All done!'
