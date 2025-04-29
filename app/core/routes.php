<?php

use App\Routes\Route;

Route::get('/', [\App\Controllers\Main::class, 'index'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getCountUsers'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getCountUsersOnline'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getListUsersByRefCode'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\StatisticsUsers::class, 'getListUsersOnlineByRefCode'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\SearchUsers::class, 'getUserByLogin'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\SearchUsers::class, 'getUserByRefCode'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getBalanceOneAndTwo'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getGeolocation'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getEmail'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'checkVPN'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getPriceByRegion'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getTrafficSold'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getWithdrawalsMoney'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getBalance'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserInfo::class, 'getRefCode'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserManagement::class, 'writeOffAmountFromBalance'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserManagement::class, 'topUpBalance'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserManagement::class, 'getHistoryDepositsWriteOffBlockings'], [\App\Middlewares\AccessControl::class]);
Route::get('/', [\App\Controllers\UserManagement::class, 'blockUser'], [\App\Middlewares\AccessControl::class]);