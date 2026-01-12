<?php

use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'Hello world!');

Route::post('/import-words', [WordController::class, 'import']);
