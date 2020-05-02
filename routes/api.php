<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'PassportController@login')->name('login');
Route::post('register', 'PassportController@register');

Route::get('authors', 'AuthorsController@getAll');
Route::get('author/{id}', 'AuthorsController@getId');
Route::post('author', 'AuthorsController@post');

Route::get('publications', 'PublicationsController@getAll');
Route::get('publication/{id}', 'PublicationsController@getId');
Route::post('publication/{type}/{autors_id}', 'PublicationsController@post');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');
});

