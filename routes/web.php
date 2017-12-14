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



Route::middleware(['auth'])->group(function () {
    Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

    Route::get('/test', function(){
        $auth = App\GoogleAuth::find(1);

        $api = new App\Services\GoogleUrlShortener($auth);

        $url = $api->shorten('https://www.southhonda.com?utm_source=house-list&utm_medium=email&utm_campaign=sales&utm_term=SESB&utm_content=12-7-17');

        dd($url);

    });


    Route::get('login/google', 'Auth\LoginController@redirectToProvider');
    Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

    Route::post('/urls/store', ['uses' => 'URLController@store']);
    Route::get('/urls/list', ['uses' => 'URLController@index']);
    Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
});


Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['uses' => 'Auth\LoginController@login']);