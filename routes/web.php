<?php

use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::get('/package', [PackageController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
