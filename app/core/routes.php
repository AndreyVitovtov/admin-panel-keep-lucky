<?php

use App\Routes\Route;

Route::get('/', [\App\Controllers\Main::class, 'index'], [\App\Middlewares\AccessControl::class]);