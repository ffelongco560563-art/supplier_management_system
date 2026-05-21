<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.inventory.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'required|string',
            'litre'           => 'required|string',
            'price_unit'      => 'required|numeric',
            'stock'           => 'required|integer',
            'expiration_date' => 'required|date',
            'image'           => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        Product::create($validated);
        return redirect()->route('admin.inventory.index')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        // Works for both normal and trashed items for the View Modal
        $product = Product::withTrashed()->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'required|string',
            'litre'           => 'required|string',
            'price_unit'      => 'required|numeric',
            'stock'           => 'required|integer',
            'expiration_date' => 'required|date',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Optional on edit
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        $product->update($validated);
        return redirect()->route('admin.inventory.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.inventory.index')->with('success', 'Product moved to archives.');
    }

    public function archived()
    {
        $archivedProducts = Product::onlyTrashed()->get();
        return view('admin.inventory.archived', compact('archivedProducts'));
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->back()->with('success', 'Product restored successfully.');
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->forceDelete(); 
        return redirect()->back()->with('success', 'Product permanently deleted.');
    }
}