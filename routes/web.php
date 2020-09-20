<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function() {
    Route::get('/{any?}', 'AuthController@index')->where('any', '.*');
});