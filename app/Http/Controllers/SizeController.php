<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('name')->get();

        return view('sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
            'code' => 'nullable|string|max:50|unique:sizes,code',
            'description' => 'nullable|string',
        ]);

        Size::create($request->only(['name', 'code', 'description']));

        return redirect()->route('sizes.index')
            ->with('success', 'Size created successfully!');
    }

    public function show(Size $size)
    {
        return view('sizes.show', compact('size'));
    }

    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,'.$size->id,
            'code' => 'nullable|string|max:50|unique:sizes,code,'.$size->id,
            'description' => 'nullable|string',
        ]);

        $size->update($request->only(['name', 'code', 'description']));

        return redirect()->route('sizes.index')
            ->with('success', 'Size updated successfully!');
    }

    public function destroy(Size $size)
    {
        if ($size->products()->exists()) {
            return redirect()->route('sizes.index')
                ->with('error', 'Cannot delete a size that has products. Reassign products first.');
        }

        $size->delete();

        return redirect()->route('sizes.index')
            ->with('success', 'Size deleted successfully!');
    }
}
