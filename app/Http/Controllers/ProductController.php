<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'max_user' => 'required|integer',
            'price' => 'required|numeric',
            'category_ids' => 'required|array',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'scheme' => 'nullable|string',
            'information' => 'nullable|string',
            'benefit' => 'nullable|array'
        ]);

        $data = $request->only(['title', 'duration', 'max_user', 'price', 'scheme', 'information']);
        $data['uuid'] = Str::uuid();
        $data['benefit'] = $request->benefit ? array_values(array_filter($request->benefit)) : null;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('products', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('products', 'public');
        }

        $product = Product::create($data);
        $product->categories()->sync($request->category_ids);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('categories');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'max_user' => 'required|integer',
            'price' => 'required|numeric',
            'category_ids' => 'required|array',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'scheme' => 'nullable|string',
            'information' => 'nullable|string',
            'benefit' => 'nullable|array'
        ]);

        $data = $request->only(['title', 'duration', 'max_user', 'price', 'scheme', 'information']);
        $data['benefit'] = $request->benefit ? array_values(array_filter($request->benefit)) : null;

        if ($request->hasFile('logo')) {
            if ($product->logo && Storage::disk('public')->exists($product->logo)) {
                Storage::disk('public')->delete($product->logo);
            }
            $data['logo'] = $request->file('logo')->store('products', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($product->banner && Storage::disk('public')->exists($product->banner)) {
                Storage::disk('public')->delete($product->banner);
            }
            $data['banner'] = $request->file('banner')->store('products', 'public');
        }

        $product->update($data);
        $product->categories()->sync($request->category_ids);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->logo) Storage::disk('public')->delete($product->logo);
        if ($product->banner) Storage::disk('public')->delete($product->banner);
        $product->categories()->detach();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
