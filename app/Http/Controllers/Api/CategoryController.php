<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); 
        return response()->json([
            'response' => 'success',
            'data' => $categories
        ]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'response' => 'success',
            'data' => $category
        ]);
    }
}
