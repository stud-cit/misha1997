<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'PassportController@login')->name('login');
Route::post('register', 'PassportController@register');

//authors
Route::get('authors', 'AuthorsController@getAll');
Route::get('persons/{name}', 'AuthorsController@getPersons');
Route::get('author/{id}', 'AuthorsController@getId');
Route::post('post-any-author', 'AuthorsController@postAnyAuthor');
Route::get('roles', 'AuthorsController@getRoles');

//notifications
Route::get('notifications/{autors_id}', 'AuthorsController@getNotifications');
Route::post('notifications/{autors_id}', 'AuthorsController@postNotifications');

//publications
Route::get('publications', 'PublicationsController@getAll');
Route::get('publication/{id}', 'PublicationsController@getId');
Route::post('publication/{type}/{autors_id}', 'PublicationsController@post');

Route::get('country', 'PublicationsController@getCountry');



Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');
});

