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
Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

// treads
Route::get('/treads', 'TreadsController@index');
Route::get('/treads/create', 'TreadsController@create');
Route::get('/treads/{channel}/{tread}', 'TreadsController@show');
Route::delete('/treads/{channel}/{tread}', 'TreadsController@destroy');

  // instead of all above
// Route::resource('treads', 'TreadsController');

Route::post('/treads', 'TreadsController@store');
Route::get('/treads/{channel}', 'TreadsController@index');
Route::post('/treads/{channel}/{tread}/replies', 'RepliesController@store');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');



