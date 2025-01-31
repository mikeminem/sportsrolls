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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'MainController@index')->name('index');

//Auth::routes(['register' => false]);
Auth::routes(['register' => false, 'password.request' => false, 'password.reset' => false]);


Route::get('/home', 'HomeController@index')->name('home');

//******************************************************************
//* all catalogues
//******************************************************************

Route::resource('/users','UsersController');
// Route::get('/users/delete/{id}', 'UserController@destroy');
// Route::get('/users/password/{id}', 'UserController@formpassword');
// Route::post('/users/password/{id}', 'UserController@changepassword');

// States
Route::resource('/states','StatesController');

// Municipalities
Route::resource('/municipalities','MunicipalitiesController');
Route::get('/municipalities_list/{state_id}','MunicipalitiesController@list');

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
Route::get('/categories_list/{tournament_id}','CategoryController@list');

//groups options
Route::resource('/groups','GroupsController');
Route::get('/get_groups/{category_id}','GroupsController@getGroups');


//profiles options
Route::resource('/profiles','ProfilesController');
Route::get('/my_profile', 'ProfilesController@show');
Route::post('/delete_profile_photo', 'ProfilesController@destroy');

//page categories
//categories options
Route::resource('/pagecategories','PageCategoryController');
Route::get('/pagecategories_list/{user_id}','PageCategoryController@list');
Route::get('/pagecategory/{slug}','PageCategoryController@showlist');

Route::resource('/webpages','WebPagesController');
Route::get('/webpages/{user_id}','WebPagesController@list');
Route::get('/webpage/{slug}','WebPagesController@showWebPage');


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

Route::get('sport/{filename}',function($filename){
    $path = storage_path("app/public/sports/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});

Route::get('team/{filename}',function($filename){
    $path = storage_path("app/public/teams/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});

Route::get('team_logo/{filename}',function($filename){
    $path = storage_path("app/public/logos/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});

Route::get('sponsor/{filename}',function($filename){
    $path = storage_path("app/public/sponsors/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});

Route::get('league_logo/{filename}',function($filename){
    $path = storage_path("app/public/league_logo/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});

Route::get('web_pages/{filename}',function($filename){
    $path = storage_path("app/public/web_pages/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});

//players photos
Route::get('players_photos/{filename}',function($filename){
    $path = storage_path("app/public/players_photos/$filename");

    if (!\File::exists($path)) abort(404);

    $file = \File::get($path);
    $type = \File::mimeType($path);

    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    return $response;
});

//photos
Route::get('photos/{filename}',function($filename){
    $path = storage_path("app/public/photos/$filename");

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
Route::get('/tournament_info/{slug}', 'TournamentsController@frontPage');
Route::get('/tournament_info/{slug}/results', 'TournamentsController@results');
Route::get('/tournament_info/{slug}/calendar', 'TournamentsController@calendar');
Route::get('/tournament_info/{slug}/standings', 'TournamentsController@standings');
Route::get('/tournament_info/{slug}/photos', 'TournamentsController@photos');
Route::get('/tournament_info/{slug}/teams', 'TournamentsController@teams');
Route::get('/tournament_info/{slug}/game/{code}', 'TournamentsController@game');
Route::get('/update_tournament_enable_enrollment/{id}/{enable_enrollment}', 'TournamentsController@updateEnableEnroll');

//teams options
Route::resource('/teams','TeamsController');
Route::get('/teams_list/{category_id}','TeamsController@list');
Route::get('/teams_players/{id}','TeamsController@playersList');
Route::get('/getTeamsbyCategoryAndBranch/{category_id}/{branch}','TeamsController@getTeamsbyCategoryAndBranch');
Route::get('/teams_list_by_branch/{tournament_id}/{branch}','TeamsController@listByBranch');
Route::get('/teams_list_by_group/{group_id}','TeamsController@listByGroup');
Route::get('/update_team_status/{id}/{status}', 'TeamsController@updateStatus');
Route::post('/teams/upload_image', 'TeamsController@uploadImage');
//Route::put('/update_brand_website/{id}/{website}', 'BrandController@updateWebsite');

Route::resource('/players','PlayersController');
Route::get('/players_list/{user_id}','PlayersController@list');

Route::resource('/pictures','PicturesController');
Route::get('/pictures_list/{user_id}','PicturesController@list');

//roster players
Route::resource('/rosters','RosterController');
Route::get('/rosters_list/{user_id}','RosterController@list');

//journeys
Route::resource('/journeys','JourneysController');
Route::get('/journaments_list/{tournament_id}','JourneysController@list');
Route::get('/update_journey_status/{id}/{status}', 'JourneysController@updateStatus');

//games
Route::resource('/games','GamesController');
Route::post('/score_game', 'GamesController@updateScore');
Route::get('/show_game/{code}', 'GamesController@showGame');
Route::get('/game_history/{code}', 'GamesController@gameHistory');
Route::post('/game_data_entry', 'GamesController@data_entry');
Route::get('/get_team_games/{teamId}', 'GamesController@getTeamGames');

//Sponsors options
Route::resource('/sponsors','SponsorController');
Route::get('/sponsors_list/{user_id}','SponsorController@list');

//admin options
//WebPagees
Route::resource('/pages','PagesController');
Route::get('/pages_list/{user_id}','PagesController@list');

//WebPagees
Route::resource('/actions','ActionsController');
Route::get('/actions_list/{user_id}','ActionsController@list');
