<?php

use Illuminate\Support\Facades\Route;

Route::get('/?mode={any}', 'PassportController@mode')->where('any', '0-9');

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
