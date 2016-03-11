<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('welcome');
});


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
	// Un protected routes
	$api->group(['prefix' => 'v1'], function ($api) {
        
        // Data Create 
        $api->post('register', 'App\Http\Controllers\UserController@doRegister');

        // Data Fetch Image 
        $api->get('image', 'App\Http\Controllers\UserController@showImage');

        //Login
        $api->post('login', ['middleware' => 'api.auth', 'uses' => 'App\Http\Controllers\UserController@doLogin']);
    });

	// Protected routes
    $api->group(['prefix' => 'v1','middleware' => 'auth.basic'], function ($api) { //, 'middleware' => 'auth.basic'
		// Data Read
        $api->get('user/{id}','App\Http\Controllers\UserController@showUser');
        $api->get('users','App\Http\Controllers\UserController@showUsers');

        // Data Update
        $api->get('user/update/{id}','App\Http\Controllers\UserController@update');

        // Data Deleter
        $api->get('user/delete/{id}','App\Http\Controllers\UserController@delete');
    });
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    
});
