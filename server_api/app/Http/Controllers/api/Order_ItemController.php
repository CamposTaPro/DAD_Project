<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order_Item;
use App\Http\Resources\Order_ItemResource;

class Order_ItemController extends Controller
{

    public function show(Order_Item $order_item){
        if ($order_item == null) {
            return response()->json(['message' => 'Order item not found'], 404);
        }
        if ($order_item->product == null) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($order_item->product);
    }

    public function store(Request $request) {
        $request->validate([
            'order_id' => 'required|numeric|min:1',
            'product_id' => 'required|numeric|min:1',
            'order_local_number' => 'required|numeric|min:1',
            'status' => 'required|string|in:R,W',
            'price' => 'required|min:0|numeric',
        ]);

        $order_item = Order_Item::create($request->all());
        return response()->json($order_item);
    }

    public function updateStatus(Request $request, Order_Item $orderItem){
        //VERIFY -adicionei ao status o W, pode nems ser preciso mas faz sentido ele poder alterar para qualquer estado
        $request->validate([
            'status' => 'required|string|in:R,P,W',
            'preparation_by' => 'required|numeric|min:1',
        ]);

        if ($orderItem == null) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

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
        //VERIFY --parece estar certo
        $request->validate([
            'status' => 'required|string|in:W',
            'type' => 'required|string|in:hot dish',
        ]);

        if ($order_item == null) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        $order_item= Order_ItemResource::collection(Order_Item::where('status', $request->status)->get()->where('product.type', $request->type));

        if ($order_item->isEmpty()) {
            return response()->json();
        }

        return response()->json($order_item);
    }

    public function show_preparing(Request $request,Order_Item $order_item){
        //VERIFY --parece estar certo
        $request->validate([
            'status' => 'required|string|in:P',
            'type' => 'required|string|in:hot dish',
        ]);

        if ($order_item == null) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        $order_item= Order_ItemResource::collection(Order_Item::where('status', $request->status)->get()->where('product.type', $request->type));

        if ($order_item->isEmpty()) {
            return response()->json();
        }

        return response()->json($order_item);
    }

    public function show_my_preparation(Request $request,Order_Item $order_item){
        //VERIFY -- parece estar certo
        $request->validate([
            'status' => 'required|string|in:P',
            'type' => 'required|string|in:hot dish',
            'preparation_by' => 'required|numeric|min:1',
        ]);

        if ($order_item == null) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        $order_item= Order_ItemResource::collection(Order_Item::where('status', $request->status)->get()->where('product.type', $request->type)->where('preparation_by', $request->preparation_by));

        if ($order_item->isEmpty()) {
            return response()->json();
        }

        return response()->json($order_item);
    }
}
