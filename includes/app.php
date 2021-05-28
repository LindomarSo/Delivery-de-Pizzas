<?php
require __DIR__.'/../vendor/autoload.php';

use \App\Common\Environment;
use \App\Database\Database;
use App\Http\Middleware\Queue;

Environment::load(__DIR__.'/../');

define('URL', getenv('URL'));

Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS')
);

//MAPEAMENTO DOS MIDDLEWARES
Queue::setMap([
    'maintenance'=>\App\Http\Middleware\Maintenance::class,
    'required-admin-login'=>\App\Http\Middleware\RequireAdminLogin::class,
    'required-admin-logout'=>\App\Http\Middleware\RequireAdminLogout::class,
]);

//MAPEAMENTO DE MIDDLEWARE PADRÃO DE TODAS AS ROTAS
Queue::setDefault([
    'maintenance'
]);