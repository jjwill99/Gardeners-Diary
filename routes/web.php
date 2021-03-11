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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('list', 'UserController@list');
Route::get('show/{id}', 'UserController@show');

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/garden', 'GardenController@index')->name('garden');
// Route::post('/save', 'GardenController@save')->name('save');

// Route::resource('grid', 'GridController');
// Route::resource('garden', 'GardenController');
// Route::resource('plant', 'PlantController');

// Route::get('garden/{garden}', ['uses' => 'GardenController@show', 'middleware' => 'AuthResource']);

// Route::get('garden/show', ['id' => '{garden}', 'uses' => 'GardenController@show', 'middleware' => 'AuthResource']);
// Route::get('garden/edit', ['id' => '{garden}', 'uses' => 'GardenController@edit', 'middleware' => 'AuthResource']);

// Route::get('/testgarden', 'GardenController@show');
// Route::post('/delete/{id}', 'GardenController@destroy');
// Route::post('/addGarden', 'GardenController@store');

Route::get('/{any}', 'SpaController@index')->where('any', '.*');