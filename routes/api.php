<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('check-user', 'AuthController@checkUser');
Route::post('register', 'AuthController@register');

//authors
Route::get('login-author', 'AuthorsController@getLoginUser');
Route::get('authors', 'AuthorsController@getAll');
Route::get('persons/{categ}/{id}', 'AuthorsController@getPersons');
Route::get('author/{id}', 'AuthorsController@getId');
Route::post('author', 'AuthorsController@postAuthor');
Route::post('alias', 'AuthorsController@postAlias');
Route::get('roles', 'AuthorsController@getRoles');

Route::post('update-author/{id}','AuthorsController@updateAuthor');
Route::post('delete-author/{id}','AuthorsController@deleteAuthor');

//notifications
Route::get('notifications/{autors_id}', 'AuthorsController@getNotifications');
Route::post('notifications/{autors_id}', 'AuthorsController@postNotifications');

//publications
Route::get('publications', 'PublicationsController@getAll');
Route::get('publication/{id}', 'PublicationsController@getId');
Route::post('publication', 'PublicationsController@post');

Route::post('update-publication/{id}','PublicationsController@updatePublication');
Route::post('delete-publication/{id}','PublicationsController@deletePublication');

Route::get('type-publications', 'PublicationsController@typePublications');
Route::get('country', 'PublicationsController@getCountry');

Route::get('export', 'PublicationsController@Export');