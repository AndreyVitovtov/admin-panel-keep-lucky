<?php

use App\Routes\Route;

Route::get('/', [\App\Controllers\Main::class, 'index'], [\App\Middlewares\AccessControl::class]);
Route::get('/users', [\App\Controllers\Users::class, 'index'], [\App\Middlewares\AccessControl::class]);
Route::get('/users/details/{id}', [\App\Controllers\Users::class, 'details'], [\App\Middlewares\AccessControl::class]);

Route::get('/users/all', [\App\Controllers\Users::class, 'all'], [\App\Middlewares\AccessControl::class]);
Route::get('/users/searchByLogin', [\App\Controllers\Users::class, 'searchByLogin'], [\App\Middlewares\AccessControl::class]);
Route::get('/users/searchByRefCode', [\App\Controllers\Users::class, 'searchByRefCode'], [\App\Middlewares\AccessControl::class]);
Route::post('/users/block', [\App\Controllers\Users::class, 'block'], [\App\Middlewares\AccessControl::class]);
Route::post('/users/topUpBalance', [\App\Controllers\Users::class, 'topUpBalance'], [\App\Middlewares\AccessControl::class]);
Route::post('/users/writeOffBalance', [\App\Controllers\Users::class, 'writeOffBalance'], [\App\Middlewares\AccessControl::class]);
Route::get('/traffic', [\App\Controllers\Traffic::class, 'stats'], [\App\Middlewares\AccessControl::class]);


//Route::get('/', [\App\Controllers\Main::class, 'index'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getCountUsers'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getCountUsersOnline'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getListUsersByRefCode'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getListUsersOnlineByRefCode'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\SearchUsers::class, 'getUserByLogin'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\SearchUsers::class, 'getUserByRefCode'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getBalanceOneAndTwo'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getGeolocation'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getEmail'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'checkVPN'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getPriceByRegion'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getTrafficSold'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getWithdrawalsMoney'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getBalance'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserInfo::class, 'getRefCode'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserManagement::class, 'writeOffAmountFromBalance'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserManagement::class, 'topUpBalance'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserManagement::class, 'getHistoryDepositsWriteOffBlockings'], [\App\Middlewares\AccessControl::class]);
//Route::get('/', [\App\Controllers\UserManagement::class, 'blockUser'], [\App\Middlewares\AccessControl::class]);