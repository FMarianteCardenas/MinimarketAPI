<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/**
*User
*/
Route::resource('users','User\UserController',['except'=>'create','edit']);

/**
*Minimarket
*/
Route::resource('minimarkets','Minimarket\MinimarketController');

/**
*Product
*/
Route::resource('products','Product\ProductController');

/**
*Category
*/
Route::resource('categories','Category\CategoryController');

/**
*Sale
*/
Route::resource('sales','Sale\SaleController');

Route::get(
        'minimarkets/{min_id}/products/{prod_id}/', [
    'uses' => 'Product\ProductController@productMinimarket',
    'as' => 'product.minimarket',
        ]
);

Route::get(
        'minimarkets/{min_id}/{code_prod}/', [
    'uses' => 'Product\ProductController@productCode',
    'as' => 'product.minimarket.code',
        ]
);

Route::post(
 'products_sale/{sale_id}', [
    'uses' => 'Sale\SaleController@saleProduct',
    'as' => 'sale.products',
        ]
);

Route::post('minimarket_product/{minimarket_id}',[
   'uses' => 'Product\ProductController@storeProductToMinimarket',
   'as' => 'minimarket.product.create'
]);

Route::post('minimarket_category/{minimarket_id}',[
   'uses' => 'Minimarket_Category\Minimarket_CategoryController@storeCategoryToMinimarket',
   'as' => 'minimarket.category.create'
]);

Route::get('minimarket_category/{minimarket_id}',[
   'uses' => 'Minimarket_Category\Minimarket_CategoryController@getCategoriesFromMinimarket',
   'as' => 'minimarket.category.get'
]);

Route::get('sale_products/{sale_id}',[
   'uses' => 'Sale\SaleController@getProductsFromSale',
   'as' => 'sale.products.get'
]);

Route::get('minimarkets/{minimarket}/product/{code}',[
   'uses' => 'Minimarket\MinimarketController@getProductByCode',
   'as' => 'minimarket.product.code.get'
]);

Route::get('minimarkets/{minimarket}/product_id/{product}',[
   'uses' => 'Minimarket\MinimarketController@getProductById',
   'as' => 'minimarket.product.id.get'
]);

/*obtiene todos los prouctos de un minimercado*/
Route::get('minimarket/{minimarket}/all_products',[
  'uses' => 'Minimarket_Product\Minimarket_ProductController@showProducts',
  'as' => 'minimarket.products.all'
]);

/*rutas de laravel passport*/
Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

/*verificacion de token correo*/
Route::get('users/verify/{token}','User\UserController@verify')->name('verify');
Route::get('users/{user}/resend','User\UserController@resend')->name('resend');


/*Iniciar Sesión en la Api y Obtener un access token*/
Route::post('users/login/api','Api\AuthController@login')->name('loginapi');
/*Cerrar Sesión en la Api y Anular el access token*/
Route::get('users/logout/api','Api\AuthController@logout')->name('logoutapi');