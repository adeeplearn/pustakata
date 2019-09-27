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

//author routes
Route::post('/author', 'AuthorsController@store');

//book routes
Route::post('/books', 'BooksController@store');
Route::patch('/books/{book}', 'BooksController@update');
Route::delete('/books/{book}', 'BooksController@destroy');

//book reserve routes
Route::post('/checkout/{book}', 'CheckoutBooksController@store');
Route::post('/checkin/{book}', 'CheckinBooksController@store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
