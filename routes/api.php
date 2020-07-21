<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('check-login', 'PassportController@checkLogin');
Route::get('check-register', 'PassportController@checkRegister');

Route::post('login', 'PassportController@login')->name('login');
Route::post('register', 'PassportController@register');

//authors
Route::get('login-author', 'AuthorsController@getLoginUser');
Route::get('authors', 'AuthorsController@getAll');
Route::get('persons/{categ}/{id}', 'AuthorsController@getPersons');
Route::get('author/{id}', 'AuthorsController@getId');
Route::post('author', 'AuthorsController@postAuthor');
Route::post('alias', 'AuthorsController@postAlias');
Route::get('roles', 'AuthorsController@getRoles');

//notifications
Route::get('notifications/{autors_id}', 'AuthorsController@getNotifications');
Route::post('notifications/{autors_id}', 'AuthorsController@postNotifications');

//publications
Route::get('publications', 'PublicationsController@getAll');
Route::get('publication/{id}', 'PublicationsController@getId');
Route::post('publication', 'PublicationsController@post');

Route::get('type-publications', 'PublicationsController@typePublications');
Route::get('country', 'PublicationsController@getCountry');

Route::get('export', 'PublicationsController@Export');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');
});

