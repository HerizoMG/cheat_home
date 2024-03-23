
# lancer le projet
php artisan serve

# creation modele
php artisan make:model NomDuModele -m

# migration BD
php artisan migrate

php artisan migrate:fresh [sady mamafa ny table rehetra]

# creation controller
php artisan make:controller API/nomController --api --model=nomModele

# authentification

composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

Route::group(['middleware' => ['auth:sanctum']], function () {
route@izay no ato
});


# ilaina ito
$table->unsignedBigInteger('collector_id');
$table->foreign('collector_id')->references('id')->on('users')->onDelete('cascade');

# Gestion de Roles
php artisan make:middleware EnsureUserHasRole
manamboatra ny middleware any @ Kernel.php
mapiditra isRole:isUser ao @ api.php

# import file excel to database
composer require psr/simple-cache:^2.0 maatwebsite/excel
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
php artisan make:import YourImportClass
manova an'iay zip extension any @ xampp/php/php.ini
https://larachamp.com/how-to-import-excel-file-to-database-in-laravel/

# expose api local
php artisan serve --host 0.0.0.0 --port=8000

# authentification google
https://laracoding.com/adding-login-with-google-to-your-laravel-app/

