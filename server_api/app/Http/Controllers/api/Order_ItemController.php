<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order_Item;
use App\Http\Resources\Order_ItemResource;

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

    public function getOrderItensByOrderId(int $id){
        $ordersItens = Order_Item::where('order_id',$id)->get();
        return response()->json($ordersItens);
    }

    public function updateStatus(Request $request, Order_Item $orderItem){
        try {
            $orderItem->status = $request->status;
            $orderItem->preparation_by = $request->preparation_by;
            $orderItem->save();
            return response()->json($orderItem);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error updating order',
                'error' => $e->getMessage()
            ], 422);
        }
    }
    public function show_hot_dish(Request $request,Order_Item $order_item){
        $order_item= Order_ItemResource::collection(Order_Item::where('status', $request->status)->get()->where('product.type', $request->type));
        return response()->json($order_item);
    }
    public function show_my_preparation(Request $request,Order_Item $order_item){
        $order_item= Order_ItemResource::collection(Order_Item::where('status', $request->status)->get()->where('product.type', $request->type)->where('preparation_by', $request->preparation_by));
        return response()->json($order_item);
    }
}
