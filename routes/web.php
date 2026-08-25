<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Master CRUD
|--------------------------------------------------------------------------
*/

Route::resource('categories', CategoryController::class);

Route::resource('sizes', SizeController::class);

Route::resource('products', ProductController::class);


/*
|--------------------------------------------------------------------------
| Stock Management
|--------------------------------------------------------------------------
*/

Route::get(
    '/stock-movements',
    [StockMovementController::class, 'index']
)->name('stock-movements.index');


Route::get(
    '/products/{product}/stock-adjustment',
    [StockMovementController::class, 'create']
)->name('stock-movements.create');


Route::post(
    '/products/{product}/stock-adjustment',
    [StockMovementController::class, 'store']
)->name('stock-movements.store');


Route::get(
    '/products/{product}/stock-history',
    [StockMovementController::class, 'productHistory']
)->name('stock-movements.product-history');