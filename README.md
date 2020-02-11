Laravel AdminLte 3
==========
An easy way to integrate [AdminLTE](https://adminlte.io) into your laravel applications.

## Installation

1 -  Install package via composer.
```
composer require laraeast/laravel-adminlte --dev
```
2 - Install scaffolding. 
```
php artisan adminlte:install
```
3 - Install `npm` dependencies
```
npm install && npm run adminlte:dev
```
4 - Runing demo page.
```
Route::get('/', function () {
    return view('layouts.adminlte.app');
});
```
