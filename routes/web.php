<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\QueryController;

// Show the home form for natural language SQL queries
Route::get('/', [QueryController::class, 'index'])->name('home');

// Handle the submitted natural language query and display SQL/results
Route::post('/query', [QueryController::class, 'process'])->name('query.process');

Route::get('/tables', [QueryController::class, 'tables'])->name('tables.view');