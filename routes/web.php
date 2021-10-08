<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function() {
  Route::middleware(['checkAdmin'])->group(function () {
    Route::get('/scopus', 'App\Http\Controllers\AuthController@index'); 
    Route::get('/archive', 'App\Http\Controllers\AuthController@index'); 
    Route::get('/rating', 'App\Http\Controllers\AuthController@index');
    Route::get('/constructor', 'App\Http\Controllers\AuthController@index');
    Route::get('/audit', 'App\Http\Controllers\AuthController@index');
  });

  Route::middleware(['checkAuthor'])->group(function () {
    Route::get('/publications', 'App\Http\Controllers\AuthController@index');
    Route::get('/user/{id}', 'App\Http\Controllers\AuthController@index')->middleware('checkAvailabilityAuthor');
    Route::get('/users', 'App\Http\Controllers\AuthController@index');
  });

  Route::get('/publication/{id}', 'App\Http\Controllers\AuthController@index')->middleware(['checkAvailabilityPublication', 'checkPublication']);
  Route::get('/publication/edit/{id}', 'App\Http\Controllers\AuthController@index')->middleware(['checkAvailabilityPublication', 'checkAccess', 'checkPublication']);
  Route::get('/add', 'App\Http\Controllers\AuthController@index')->middleware('checkAccess');

  Route::get('/{any?}', 'App\Http\Controllers\AuthController@index')->where('any', '.*');
});