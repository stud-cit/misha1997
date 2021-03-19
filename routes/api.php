<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Авторизация юзера
Route::get('check-user', 'AuthController@checkUser');
Route::post('register', 'AuthController@register');
Route::post('logout', 'AuthController@logout');

Route::get('scopus', 'DevController@scopus');
Route::get('scopus-test', 'DevController@scopusTest');

Route::group(['middleware' => ['web']], function() {

    Route::get('job-type', 'AuthorsController@jobType');

    // Режим доступу
    Route::get('access', 'ServiceController@access');
    Route::post('access', 'ServiceController@changeAccess');

    //authors
    Route::get('authors', 'AuthorsController@get');
    Route::get('authors-all', 'AuthorsController@getAll');
    Route::get('others-authors-name', 'AuthorsController@getOtherAuthorNames');
    Route::get('persons/{categ}/{id}', 'AuthorsController@getPersons');

    //author
    Route::post('author', 'AuthorsController@postAuthor');
    Route::post('author-ssu', 'AuthorsController@postAuthorSSU');
    Route::get('author/{id}', 'AuthorsController@getId');
    Route::post('update-author/{id}','AuthorsController@updateAuthor');
    Route::post('delete-users','AuthorsController@deleteAuthor');

    Route::post('update-cabinet-info/{user_id}','AuthorsController@updateCabinetInfo');

    // profile
    Route::post('profile', 'AuthorsController@updateProfile');
    Route::get('profile', 'AuthorsController@profile');

    // roles
    Route::get('roles', 'AuthorsController@getRoles');

    //notifications
    Route::get('notifications/{autors_id}', 'AuthorsController@getNotifications');
    Route::post('notifications/{publication_id}', 'AuthorsController@postNotifications');
    Route::post('notifications/{id}/{autors_id}', 'AuthorsController@editNotifications');
    Route::delete('notifications/{id}/{autors_id}', 'AuthorsController@deleteNotifications');
    Route::delete('notifications/{autors_id}', 'AuthorsController@deleteAllNotifications');

    //publications
    Route::get('publications-names', 'PublicationsController@getAllNames'); // всі назви публікацій
    Route::get('publications/{author_id?}', 'PublicationsController@getAll'); // всі публікації з додатковими залежностями
    Route::get('publication/{id}', 'PublicationsController@getId');
    Route::post('publication', 'PublicationsController@post');
    Route::get('check-publication/{id}', 'PublicationsController@checkPublication');

    Route::post('update-publication/{id}','PublicationsController@updatePublication');
    Route::post('delete-publications','PublicationsController@deletePublications');
    Route::post('delete-publication/{id}','PublicationsController@deletePublication');

    Route::get('type-publications', 'PublicationsController@typePublications');
    Route::get('country', 'PublicationsController@getCountry');

    Route::post('export', 'PublicationsController@export');

    // ASU
    Route::get('sort-divisions', 'ASUController@getSortDivisions');
    Route::get('divisions', 'ASUController@getDivisions');

    // audit
    Route::get('audit', 'AuditController@get');
});
