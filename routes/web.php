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
        $collection = \App\Collection::first();

        dd($collection->urls);

    });


    Route::get('login/google', 'Auth\LoginController@redirectToProvider');
    Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

    Route::post('/urls/store', ['uses' => 'URLController@store']);
    Route::get('/urls/list', ['uses' => 'URLController@index']);
    Route::post('/urls/update/{collection}', ['uses' => 'URLController@update']);
    Route::post('/urls/destroy/{collection}', ['uses' => 'URLController@destroy']);
    Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
});

Route::get('/', function(){
    if(\Auth::check()){
        return redirect()->route('home');
    }else{
        return redirect()->route('login');
    }
});

Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['uses' => 'Auth\LoginController@login']);