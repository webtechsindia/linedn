<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


//Route::resource('posts', 'PostsController');


Route::any('/', 'UsersController@login');
Route::any('/login', 'UsersController@login');
Route::any('/logout', 'UsersController@logout');

Route::any('/facebook', 'FacebookController@getFBaccessToken');
Route::any('/twitter', 'TwitterController@gettwLoginLink');
Route::any('/tw/access', 'TwitterController@getTWAccessToken');
Route::any('/linkedin', 'LinkedinController@getlkLoginLink');
Route::any('/lk/access', 'LinkedinController@getlkAccessToken');

Route::group(array('before' => 'auth'), function()
{
	Route::any('/posts', 'PostController@index');
	Route::any('/readstream', 'PostsController@readstream');

	
});
App::bind('FBaccess', function()
{
    return new FacebookController;
});
App::bind('twaccess', function()
{
    return new TwitterController;
});
App::bind('lkaccess', function()
{
    return new linkedinController;
});

	//Route::any('/posts', 'PostController@index');

Route::any('/error', function(){
	return View::make('errors.loginerror');
});

// App::missing(function($exception)
// {
//     return Response::view('The page was not found'	, array(), 404);
// });

