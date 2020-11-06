<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return '<html><body>Página Inválida</body></html>';
});

Route::any('/enableDelivery/{filter?}', 'SincronazedProductsController@index')->name('ava.deliveryenable');

Route::any('/testRemote', 'SincronazedProductsController@testRemote')->name('ava.testRemote');

Route::any('/logs', 'LogsController@index')->name('log.index');

