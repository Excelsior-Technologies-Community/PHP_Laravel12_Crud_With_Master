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

        $activeProducts = Product::where('status', true)->count();

        $inactiveProducts = Product::where('status', false)->count();

        $totalCategories = Category::count();

        $totalSizes = Size::count();

        $totalStock = Product::sum('stock_quantity');

        $lowStockProducts = Product::whereColumn(
            'stock_quantity',
            '<=',
            'min_stock'
        )
            ->where('stock_quantity', '>', 0)
            ->with(['category', 'size'])
            ->get();

        $outOfStockProducts = Product::where(
            'stock_quantity',
            0
        )
            ->with(['category', 'size'])
            ->get();

        return view(
            'dashboard',
            compact(
                'totalProducts',
                'activeProducts',
                'inactiveProducts',
                'totalCategories',
                'totalSizes',
                'totalStock',
                'lowStockProducts',
                'outOfStockProducts'
            )
        );
    }
}