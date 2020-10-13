<?php

use Illuminate\Support\Facades\Route;

Route::get('/cabinet/{key}', 'AuthController@test')->where('any', '.*');

Route::group(['middleware' => ['web']], function() {
    Route::get('/{any?}', 'AuthController@index')->where('any', '.*');
});
