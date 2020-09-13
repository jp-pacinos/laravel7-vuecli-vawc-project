## Violence Against Women and Children Project

### Setup

The project is built in `laravel 7` and `vue-cli`.

-   `cd 'projectname'`
-   `composer install` (requires Composer: https://getcomposer.org)
-   Rename `.env.example` to `.env`
-   Configure `.env`, database, url, appname
-   `php artisan key:generate`
-   `php artisan migrate:fresh --seed` we make sure this is the first time
-   `php artisan serve` to start the app

### Package Included

-   [laravel/excel](https://github.com/Maatwebsite/Laravel-Excel)

### Routes

-   `/`
-   `/admin`

### Database Seeds

-   Administrator: email = admin@admin.com, password = password
