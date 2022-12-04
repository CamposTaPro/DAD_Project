<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order_Item;

class Order_ItemController extends Controller
{
    public function index(Request $request){
        $order_items = Order_Item::all();
        return response()->json($order_items);
    }

    public function show(Request $request, Order_Item $order_item){
        return response()->json($order_item->product);
    }

    public function store(Request $request) {
        $order_item = Order_Item::create($request->all());
        return response()->json($order_item);
    }

    public function updateStatus(Request $request, Order_Item $orderItem){
        $orderItem->status = $request->status;
        $orderItem->save();
        return response()->json($orderItem);
    }
}
