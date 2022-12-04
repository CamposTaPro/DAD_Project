<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order_Item;

class Order_ItemController extends Controller
{
    public function store(Request $request) {
        $order_item = Order_Item::create($request->all());
        return response()->json($order_item);
    }
}
