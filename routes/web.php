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

Route::resource('/users','UsersController');
// Route::get('/users/delete/{id}', 'UserController@destroy');
// Route::get('/users/password/{id}', 'UserController@formpassword');
// Route::post('/users/password/{id}', 'UserController@changepassword');


//stadiums options
Route::resource('/stadiums','StadiumsController');
Route::get('all_stadiums', 'StadiumsController@allStadiums');
Route::post('save_stadium', 'StadiumsController@save');
Route::get('get_stadium/{id}', 'StadiumsController@show');

//league options
Route::resource('/leagues','LeaguesController');
Route::get('league_info', 'LeaguesController@show');

//sports options
Route::resource('/sports','SportController');

//categories options
Route::resource('/categories','CategoryController');

//profiles options
Route::resource('/profiles','ProfilesController');
Route::get('/my_profile', 'ProfilesController@show');
Route::post('/delete_profile_photo', 'ProfilesController@destroy');

//show photo profile
Route::get('photo/{filename}',function($filename){
    $path = storage_path("app/public/profiles/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});



//tournaments options
Route::resource('/tournaments','TournamentsController');
Route::post('/tournaments.start/{id}', 'TournamentsController@start');


//teams options
Route::resource('/teams','TeamsController');