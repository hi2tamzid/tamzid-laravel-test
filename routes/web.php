<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/data', [IndexController::class, 'showData'])->name('data.show');
Route::post('/form-submit', [IndexController::class, 'submitForm'])->name('form.submit');
Route::post('/data-update', [IndexController::class, 'updateData'])->name('data.update');
