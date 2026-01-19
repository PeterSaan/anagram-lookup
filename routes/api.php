<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'Hello world!');

Route::controller(WordController::class)->group(function () {
    Route::get('/find-anagrams/{word}', 'find');
    Route::post('/import-words', 'import');
});

Route::controller(JobController::class)->group(function () {
    Route::get('/job/batch-progress/{id}', 'batchProgress')->whereUuid('id');
});
