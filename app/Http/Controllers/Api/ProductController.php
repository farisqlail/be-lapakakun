<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->get();

        return response()->json([
            'response' => 'success',
            'data' => ProductResource::collection($products)
        ]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'response' => 'success',
            'data' => $product
        ]);
    }
}
