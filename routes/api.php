<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Авторизация юзера
Route::get('check-user', 'AuthController@checkUser');
Route::post('register', 'AuthController@register');

Route::group(['middleware' => ['web']], function() {

    // Режим доступа
    Route::get('access', 'ServiceController@access');
    Route::post('access', 'ServiceController@changeAccess');

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
    Route::post('notifications/{id}/{autors_id}', 'AuthorsController@editNotifications');

    //publications
    Route::get('publications', 'PublicationsController@getAll');
    Route::get('publication/{id}', 'PublicationsController@getId');
    Route::post('publication', 'PublicationsController@post');

    Route::post('update-publication/{id}','PublicationsController@updatePublication');
    Route::post('delete-publications','PublicationsController@deletePublications');

    Route::get('type-publications', 'PublicationsController@typePublications');
    Route::get('country', 'PublicationsController@getCountry');

    Route::get('export', 'PublicationsController@Export');
});