# Laravel 7 + Sanctum and Vue-cli + Vuetify Project

This is example project built in `Laravel 7` with [`sanctum`](https://laravel.com/docs/7.x/sanctum) as api and [`vue-cli`](https://github.com/vuejs/vue-cli) for frontend. It also have [`laravel/excel`](https://github.com/Maatwebsite/Laravel-Excel) for simple export of records.

### Quick Install

The env.example is already configured so you can run the app locally. You may want to change it.

```sh
cd "projectname"

composer install

# create database.sqlite inside database folder then
# change .env.example to .env and generate a key
php artisan key:generate

# populate tables
php artisan migrate:fresh --seed

# start development server
php artisan serve
```

### Steps for Installation

-   `cd 'projectname'`
-   `composer install`
-   Rename `.env.example` to `.env`
-   Configure `.env`, database, url, appname
-   `php artisan key:generate`
-   `php artisan migrate:fresh --seed` we make sure this is the first time
-   `php artisan serve` to start the app

### Features

-   Restful api
-   Simple Authentication (login)
-   CRUD for Records
-   Search in User Records
-   Complain page on homepage
-   Export Records
-   And more...

### Package Included

-   laravel/excel

### Routes

-   `/`
-   `/admin`

### Database Seeds

-   Administrator: email = admin@admin.com, password = password
