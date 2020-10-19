<?php

use Illuminate\Support\Facades\Route;

Route::get('/{key}', 'AuthController@checkCabinet')->where('key', '.*');

Route::group(['middleware' => ['web']], function() {
    Route::get('/{any?}', 'AuthController@index')->where('any', '.*');
});
