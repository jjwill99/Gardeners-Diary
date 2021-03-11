<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Gardens Page
Route::get('/getGardens', 'GardenController@index');
Route::post('/deleteGarden', 'GardenController@destroy');
Route::post('/addGarden', 'GardenController@store');

//Garden Page
Route::get('/getGarden', 'GardenController@getGarden');
Route::get('/getActivities', 'PlantActivityController@getActivities');
Route::post('/addActivity', 'PlantActivityController@store');
Route::post('/completeActivity', 'PlantActivityController@complete');
Route::post('/updateActivity', 'PlantActivityController@update');
Route::post('/deleteActivity', 'PlantActivityController@destroy');

//Edit Garden Page
Route::post('/updateGarden', 'GardenController@update');
Route::post('/addPlant', 'PlantController@store');
Route::get('/getPlants', 'PlantController@getPlants');
Route::get('/getPlantLocations', 'PlantController@getLocations');
Route::post('/deletePlant', 'PlantController@destroy');