<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order_Item;

class Order_ItemController extends Controller
{
    public function store(Request $request) {
        $order_item = new Order_Item();
        $order_item->order_id = $request->order_id;
        $order_item->order_local_number = $request->order_local_number;
        $order_item->product_id = $request->product_id;
        $order_item->status = $request->status;
        $order_item->price = $request->price;
        $order_item->preparation_by = $request->preparation_by;
        $order_item->notes = $request->notes;
        $order_item->save();

        return response()->json($order_item);
    }
}
