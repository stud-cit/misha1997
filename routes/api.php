<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Авторизация юзера
Route::get('check-user', 'AuthController@checkUser');
Route::post('register', 'AuthController@register');
Route::post('logout', 'AuthController@logout');

Route::group(['middleware' => ['web']], function() {

    // Режим доступа
    Route::get('access', 'ServiceController@access');
    Route::post('access', 'ServiceController@changeAccess');

    //authors
    Route::get('authors', 'AuthorsController@get');
    Route::get('authors-all', 'AuthorsController@getAll');
    Route::get('persons/{categ}/{id}', 'AuthorsController@getPersons');

    //author
    Route::post('author', 'AuthorsController@postAuthor');
    Route::get('author/{id}', 'AuthorsController@getId');
    Route::post('update-author/{id}','AuthorsController@updateAuthor');
    Route::post('delete-author/{id}','AuthorsController@deleteAuthor');

    // profile
    Route::post('profile', 'AuthorsController@updateProfile');
    Route::get('profile', 'AuthorsController@profile');

    // roles
    Route::get('roles', 'AuthorsController@getRoles');

    //notifications
    Route::get('notifications/{autors_id}', 'AuthorsController@getNotifications');
    Route::post('notifications/{autors_id}', 'AuthorsController@postNotifications');
    Route::post('notifications/{id}/{autors_id}', 'AuthorsController@editNotifications');

    //publications
    Route::get('publications-names', 'PublicationsController@getAllNames'); // всі назви публікацій
    Route::get('publications', 'PublicationsController@getAll'); // всі публікації з додатковими залежностями
    Route::get('publication/{id}', 'PublicationsController@getId');
    Route::post('publication', 'PublicationsController@post');

    Route::post('update-publication/{id}','PublicationsController@updatePublication');
    Route::post('delete-publications','PublicationsController@deletePublications');

    Route::get('type-publications', 'PublicationsController@typePublications');
    Route::get('country', 'PublicationsController@getCountry');

    Route::post('export', 'PublicationsController@export');

    // ASU
    Route::get('sort-divisions', 'ASUController@getSortDivisions');
    Route::get('divisions', 'ASUController@getDivisions');
});
