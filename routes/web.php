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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//******************************************************************
//* categorias for ligues
//******************************************************************

Route::resource('/users','UserController');
Route::get('/users/delete/{id}', 'UserController@destroy');
Route::get('/users/password/{id}', 'UserController@formpassword');
Route::post('/users/password/{id}', 'UserController@changepassword');



Route::resource('/stadiums','StadiumsController');
Route::get('all_stadiums', 'StadiumsController@allStadiums');
Route::post('save_stadium', 'StadiumsController@save');
Route::get('get_stadium/{id}', 'StadiumsController@show');
Route::post('delete_stadium', 'StadiumsController@destroy');

// Route::get('/users/delete/{id}', 'UserController@destroy');
// Route::get('/users/password/{id}', 'UserController@password');
// Route::post('/users/password/{id}', 'UserController@changepassword');
// Route::get('/register/verify/{code}', 'UserController@verify');
