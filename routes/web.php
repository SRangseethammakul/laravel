<?php

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

Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('/category/{id}', 'WelcomeController@show')->name('welcome.show');


Route::get('/about', function () {
    $email = 'act@hotmail.com';
    $tel = '0825474891';
    $drinks = ['coke' , 'pepsi' , 'fanta'];
    return view('about.about',[
        'email' => $email,
        'tel' => $tel,
        'drinks' => $drinks
    ]);
})->name('about');

Route::get('/product/{id?}', function ($id=null) {
    return 'Product'.$id; //optional parameter
})->name('product');


Route::get('contact', 'ContactController@index')->name('contact.index');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/backend/category', 'CategoryController@index')->middleware('auth')->name('category.index');

Route::get('/backend/category/store', 'CategoryController@store')->middleware('auth');

Route::resource('/backend/product', 'ProductController')->middleware('auth');
