<?php

use App\Http\Controllers\InertiaController;
use Illuminate\Support\Facades\Route;

Route::controller(InertiaController::class)->group(function () {
    Route::get('/', 'home');

    Route::get('/anagram/find', 'anagramFind');
    Route::get('/anagram/import', 'anagramImport');
});
