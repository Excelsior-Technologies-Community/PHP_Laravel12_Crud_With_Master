<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSizes = Size::count();

        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'min_stock')
            ->where('stock_quantity', '>', 0)
            ->with(['category', 'size'])
            ->get();

        $outOfStockProducts = Product::where('stock_quantity', 0)
            ->with(['category', 'size'])
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSizes',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
}
