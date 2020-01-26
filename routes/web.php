<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//TESTING ONLY
$router->get('/',function(){
	return '
		<a href="/bayar"><h2>Test Bayar</h2></a>
		<a href="/ongkir"><h2>Test Cek Ongkir</h2></a>
	';
});
$router->get('/getProvinces','OngkirController@getProvinces');
	$router->get('/getCities/{id}','OngkirController@getCities');
	$router->post('/calcOngkir','OngkirController@calc');
	$router->get('/ongkir','OngkirController@ongkirTest');

	$router->get('/bayar','MidtransController@bayarTest'); 

$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');
$router->get('test',function(){
	dd(Illuminate\Support\Facades\Hash::make("asdf"));
});


$router->group(['middleware' => 'auth'], function () use ($router) { //di-off buat testing
	$router->get('/check-token','AuthController@checkToken');
	$router->get('/user/{id}','UsersController@getInfo');

	$router->post('/products/create','ProductsController@create');
	$router->post('/products/{id}/update','ProductsController@update');
	$router->post('/products/{id}/delete','ProductsController@delete');
	$router->get('/products','ProductsController@getProducts');
	$router->get('/products/{id}','ProductsController@getProduct');
	$router->get('/products/{id}/image','ProductsController@getMainImage');
	$router->get('/products/{id}/images','ProductsController@getImages');

	$router->post('/transactions/create','TransactionController@create');
	$router->post('/transactions/update/{id}','TransactionController@update');
	$router->post('/transactions/delete/{id}','TransactionController@delete');
});