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

Route::get(
    '/',
    [DashboardController::class, 'index']
)->name('dashboard');


/*
|--------------------------------------------------------------------------
| Master CRUD
|--------------------------------------------------------------------------
*/

Route::resource(
    'categories',
    CategoryController::class
);

Route::resource(
    'sizes',
    SizeController::class
);

Route::resource(
    'products',
    ProductController::class
);


/*
|--------------------------------------------------------------------------
| Product Extra Features
|--------------------------------------------------------------------------
*/

Route::get(
    '/products-export',
    [ProductController::class, 'export']
)->name('products.export');

Route::delete(
    '/products-bulk-delete',
    [ProductController::class, 'bulkDelete']
)->name('products.bulk-delete');

Route::patch(
    '/products/{product}/toggle-status',
    [ProductController::class, 'toggleStatus']
)->name('products.toggle-status');


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
    '/stock-movements/export',
    [StockMovementController::class, 'export']
)->name('stock-movements.export');

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
