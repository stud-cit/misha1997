<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Авторизация юзера

Route::post('register', 'App\Http\Controllers\AuthController@register');
Route::post('logout', 'App\Http\Controllers\AuthController@logout');

Route::get('supervsior', 'App\Http\Controllers\DevController@supervsior');
Route::get('pub-scopus-type', 'App\Http\Controllers\DevController@publicationType');

Route::group(['middleware' => ['web']], function() {
    // Dev
    Route::post('dev', 'App\Http\Controllers\DevController@set');
    
    // ASU
    Route::get('sort-divisions', 'App\Http\Controllers\ASUController@getSortDivisions');

    Route::get('patent-types', 'App\Http\Controllers\ServiceController@getPatentTypes');
    Route::get('science-types', 'App\Http\Controllers\ServiceController@getScienceTypes');
    Route::get('country', 'App\Http\Controllers\ServiceController@getCountry');
    Route::get('type-publications', 'App\Http\Controllers\ServiceController@typePublications');
    Route::get('roles', 'App\Http\Controllers\ServiceController@getRoles');
    Route::get('job-type', 'App\Http\Controllers\ServiceController@jobType');

    // publication
    Route::get('publications-names', 'App\Http\Controllers\PublicationsController@getAllNames');
    Route::get('publications/{author_id?}', 'App\Http\Controllers\PublicationsController@get');
    Route::get('publication/{id}', 'App\Http\Controllers\PublicationsController@getId');
    Route::post('publication', 'App\Http\Controllers\PublicationsController@post');
    Route::get('check-publication/{id}', 'App\Http\Controllers\PublicationsController@checkPublication');

    Route::get('publications-scopus', 'App\Http\Controllers\PublicationsController@getPublicationsScopus');
    Route::get('publications-scopus-user', 'App\Http\Controllers\PublicationsController@getPublicationsScopusUser');

    Route::post('update-publication/{id}','App\Http\Controllers\PublicationsController@updatePublication');
    Route::post('delete-publications','App\Http\Controllers\PublicationsController@deletePublications');
    Route::post('restore-publications','App\Http\Controllers\PublicationsController@restorePublications');

    //authors
    Route::get('users', 'App\Http\Controllers\AuthorsController@get');
    Route::get('user/{id?}', 'App\Http\Controllers\AuthorsController@getId');
    Route::post('author', 'App\Http\Controllers\AuthorsController@postAuthor');
    Route::post('author-ssu', 'App\Http\Controllers\AuthorsController@postAuthorSSU');
    Route::get('authors-all', 'App\Http\Controllers\AuthorsController@getAll');
    Route::get('others-authors-name', 'App\Http\Controllers\AuthorsController@getOtherAuthorNames');
    Route::get('persons/{categ}/{id}', 'App\Http\Controllers\AuthorsController@getPersons');
    Route::post('profile', 'App\Http\Controllers\AuthorsController@updateProfile');
    Route::post('update-author/{id}','App\Http\Controllers\AuthorsController@updateAuthor');
    Route::post('delete-users','App\Http\Controllers\AuthorsController@deleteAuthor');

    Route::post('update-cabinet-info/{user_id}','App\Http\Controllers\AuthorsController@updateCabinetInfo');

    Route::get('notifications', 'App\Http\Controllers\AuthorsController@getNotifications');
    Route::post('notifications/{publication_id}', 'App\Http\Controllers\AuthorsController@postNotifications');
    Route::post('notifications/{id}/{autors_id}', 'App\Http\Controllers\AuthorsController@editNotifications');
    Route::delete('notifications/{id}/{autors_id}', 'App\Http\Controllers\AuthorsController@deleteNotifications');
    Route::delete('notifications/{autors_id}', 'App\Http\Controllers\AuthorsController@deleteAllNotifications');

    Route::get('audit', 'App\Http\Controllers\AuditController@get');

    // Режим доступу
    Route::get('access', 'App\Http\Controllers\ServiceController@access');
    Route::post('access', 'App\Http\Controllers\ServiceController@changeAccess');
});