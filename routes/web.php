<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // <-- the blade file with your @vite('resources/js/app.js')
})->where('any', '^(?!api).*');
