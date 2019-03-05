# APP

git clone https://github.com/vvamondes/podflix.git

cd podflix/

cp .env.dev .env

composer install

composer dump-autoload

php artisan migrate --seed

php artisan cache:clear

php artisan serve

chmod 755 storage -R


## CRONTAB - FEED CRAWLER

*/10 * * * * php /DIRETORIO_PARA_SITE/artisan db:seed --class=EpisodesTableSeeder >/dev/null 2>&1

5 * * * * php /DIRETORIO_PARA_SITE/artisan db:seed --class=ProgramsTableSeeder >/dev/null 2>&1


## Desenvolvido com Laravel

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
