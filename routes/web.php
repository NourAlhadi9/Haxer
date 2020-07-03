<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' =>'web'], function(){
    Route::get('/', function() { return view('welcome'); } )->name('home');
    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::get('/login', '\App\Http\Controllers\Auth\LoginController@login');
    
    Auth::routes(['verify' => true]);


    Route::group(['middleware' => ['auth', 'verified']],function(){
        // My Teams
        Route::get('/teams/my', 'MyTeamController@index')->name('myteams.index');
        Route::post('/teams/new', 'MyTeamController@store')->name('myteams.create');
        Route::post('/teams/update/{tid}', 'MyTeamController@update')->name('myteams.update');
        Route::delete('/teams/delete/{tid}', 'MyTeamController@delete')->name('myteams.delete');
        Route::post('/teams/kick/{tid}/{uid}', 'MyTeamController@kick')->name('myteams.kick');
        Route::post('/teams/leave/{tid}/{uid}', 'MyTeamController@leave')->name('myteams.leave');
        
        // Teams
        Route::get('/teams', 'TeamController@index')->name('teams.index');
        Route::post('/teams/join/{tid}/{uid}', 'TeamController@join')->name('teams.join');

        // contests
        Route::get('/contests', 'ContestController@index')->name('contests.index');

    });


});

Route::fallback(function() {
    return redirect()->route('home');
});