<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Route;

Route::resource('categories', CategoryController::class);
Route::resource('sizes', SizeController::class);
Route::resource('products', ProductController::class);

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
