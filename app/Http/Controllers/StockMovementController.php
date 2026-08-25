<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    /**
     * Display stock movement history.
     */
    public function index(Request $request)
    {
        $movements = StockMovement::with('product')
            ->when($request->input('search'), function ($query, $search) {
                $query->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('sku', 'like', '%'.$search.'%');
                });
            })
            ->when($request->input('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('stock_movements.index', compact('movements'));
    }

    /**
     * Show stock adjustment form.
     */
    public function create(Product $product)
    {
        return view('stock_movements.create', compact('product'));
    }

    /**
     * Store a stock movement and update product stock.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|in:stock_in,stock_out,adjustment',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $quantity = (int) $request->quantity;

        if ($request->type === 'stock_out' && $quantity > $product->stock_quantity) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => 'Stock out quantity cannot be greater than current stock ('.$product->stock_quantity.').',
                ]);
        }

        DB::transaction(function () use ($request, $product, $quantity) {
            $previousStock = $product->stock_quantity;

            $newStock = match ($request->type) {
                'stock_in' => $previousStock + $quantity,

                'stock_out' => $previousStock - $quantity,

                'adjustment' => $quantity,

                default => $previousStock,
            };

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $request->type,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $request->reason,
            ]);

            $product->update([
                'stock_quantity' => $newStock,
            ]);
        });

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Stock updated successfully!');
    }

    /**
     * Display stock history for a specific product.
     */
    public function productHistory(Product $product)
    {
        $movements = $product->stockMovements()
            ->paginate(15);

        return view(
            'stock_movements.product-history',
            compact('product', 'movements')
        );
    }
}