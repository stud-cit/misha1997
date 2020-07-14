<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PassportController@index');

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
