<?php

use App\Http\Controllers\InertiaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InertiaController::class, 'home']);

Route::get('/anagram/find', [InertiaController::class, 'anagramFind']);
Route::get('/anagram/import', [InertiaController::class, 'anagramImport']);
