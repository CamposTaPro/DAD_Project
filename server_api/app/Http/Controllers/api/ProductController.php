<?php

namespace App\Http\Controllers\api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function getProducts() {
        $products = Product::all();
        return response()->json($products);
    }

    public function getProductByType(string $type) {
        $products = Product::where('type', $type)->get();
        return response()->json($products);
    }

}
