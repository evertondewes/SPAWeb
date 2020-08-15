<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::any('/enableDelivery/{filter?}', 'SincronazedProductsController@index')->name('ava.deliveryenable');
Route::any('/enableDelivery/update/{cod_barra}', 'SincronazedProductsController@index')->name('ava.deliveryenable');



//Route::get('/enableDelivery', function () {
//    return view('ava.deliveryenable');
//});

Route::get('/testeRest', function () {

    $restClient = new \App\Http\Controllers\RestClient(
        'https://api.meentrega.com/',
        '',
        '7008afd69410675f6cf3e0432b83cabde92121498776ba5821628c3ac3eafcb0',
        true
    );
    //POST /etestkey
    //POST /estoque_item_preco

 // {"acao":"novo", "barcode":"7896026301428", "name":"Teste Winner", "price":"100.00", "in_stock":"1", "update_if_exists":"true", "id_estoque_medida":"1"},

    $restClient->recurso = 'testkey';
    $resultLogin = $restClient->listar();

    //7896026301428
    $restClient->recurso = 'estoque_item_preco';
    $resultPost = $restClient->post('{"acao":"editar", "barcode":"7896026301428", "name":"Teste Winner", "price":"100.00", "in_stock":"1"');

    //7896026301428

    return '<html><body>' . print_r($resultLogin, true) . '<br>' . print_r($resultPost, true) . '<br>' . '</body></html>';
});
