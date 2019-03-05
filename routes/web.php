<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', 'FrontEndController@home');
Route::get('/home', 'FrontEndController@home');

//LOGIN
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');
Auth::routes();
Route::get('user/activation/{token}', 'Auth\LoginController@activateUser')->name('user.activate');

//SUBSCRIPTION MANAGER
Route::get('/subscription_manager', 'FrontEndController@subscription_manager');

//PLAYLIST
Route::get('/playlist', 'FrontEndController@playlist');

//SUBSCRIPTIONS EPISODES
Route::get('/subscriptions', 'FrontEndController@subscriptions');

//TRENDING/FEATURED/NEWBIES/DROPS
Route::get('/trending', 'FrontEndController@trending');
Route::get('/featured', 'FrontEndController@featured');
Route::get('/newbies', 'FrontEndController@newbies');
Route::get('/drops', 'FrontEndController@drops');

//SEARCH
Route::get('/search', 'FrontEndController@search');

//CATALOG
Route::get('/episodes/{catalog}', 'FrontEndController@catalog');


//LOGGED IN ONLY
Route::group(['middleware' => 'auth'], function () {

	//API - no slashs in the end, htaccess redirect to 301

	//ADMIN CONFIGS
	Route::get('/admin/catalogs', 'CatalogController@admin');
	Route::post('/admin/catalogs', 'CatalogController@admin_save');

	//USER PROGRAM REQUESTS
    Route::get('/feed/request/program/create', 'FrontEndController@feedRequestProgramCreate');
	Route::post('/feed/request/program/create', 'FrontEndController@userProgramRequestCreate');
	Route::get('/api/feed/request/program/create/get', 'FeedController@feedRequestProgramCreateCheck');
	Route::get('/admin/feed/request/program/create/{id}', 'FeedController@createProgramsAndEpisodesFromUserProgramRequestId');

	//API
	Route::post('/api/episode/like', 'EpisodeController@like');
	Route::post('/api/episode/dislike', 'EpisodeController@dislike');
	Route::post('/api/program/subscribe', 'ProgramController@subscribe');
	Route::post('/api/program/unsubscribe', 'ProgramController@unsubscribe');
	Route::post('/api/playlist/add', 'PlaylistController@add');
	Route::post('/api/playlist/remove', 'PlaylistController@remove');
	Route::get('/api/playlist/get', 'PlaylistController@get');

});

//API NO NEED LOGGED IN
Route::post('/api/episode/play', 'EpisodeController@play');
Route::post('/api/episode/finish', 'EpisodeController@finish');


//FEED - CRONS
Route::get('/feed', 'FeedController@index');
Route::get('/feed/load/infos/program/{id}', 'FeedController@updateProgramInfosFromProgramId');
Route::get('/feed/load/episodes/program/{id}', 'FeedController@loadEpisodes');
Route::get('/feed/load/program/create', 'FeedController@loadProgramAndEpisodesFromFeed');

//PROGRAM
Route::get('/{program}', 'FrontEndController@program');
Route::get('/{program}/{episode}', 'FrontEndController@episode');




