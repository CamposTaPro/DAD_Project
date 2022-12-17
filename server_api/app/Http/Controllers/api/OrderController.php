<?php

namespace App\Http\Controllers\api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Order_Item;
use App\Models\Product;
class OrderController extends Controller
{
    public function index() {
        $orders = Order::all();

        return response()->json($orders);
    }

    public function getOrderByStatus(string $status) {
        $orders = Order::where('status', $status)->get();


        foreach ($orders as $order) {
        $ordersItens = Order_Item::where('order_id',$order->id)->get();
        $order->order_itens = $ordersItens;
        }

        foreach($ordersItens as $pratos){
            $product = Product::where('id',$pratos->product_id)->get();
            $pratos->product = $product;
        }
        return response()->json($orders);
    }

    public function getOrderPending(){
        $orders = Order::where('status', 'P')->orWhere('status', 'R')->get();


        foreach ($orders as $order) {
        $ordersItens = Order_Item::where('order_id',$order->id)->get();
        $order->order_itens = $ordersItens;
        foreach($ordersItens as $pratos){
            $product = Product::where('id',$pratos->product_id)->get();
            $pratos->product = $product;
        }
        }

        return response()->json($orders);
    }

    public function updateStatus(Request $request, Order $order){
        try {
            $order->status = $request->status;
            $order->save();
            return response()->json($order);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error updating order',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function store(Request $request) {
        //VERIFY
        $request->validate([
            'ticket_number' => 'required|numeric|min:1|max:99',
            'status' => 'required|in:P,R,C,D',
            'customer_id' => '',
            'total_price' => 'required|numeric',
            'total_paid' => 'required|numeric',
            'total_paid_with_points' => 'required',
            'points_gained' => 'required',
            'points_used_to_pay' => 'required',
            'payment_type' => 'required',
            //'payment_reference' => [Rule::when($request->payment_type == 'VISA', 'required|numeric|digits:16','required') , Rule::when($request->default_payment_type == 'PAYPAL', 'required|email','required'),Rule::when($request->default_payment_type == 'MBWAY', 'required|numeric|doesnt_start_with:0|regex:/^([0-9\s\-\+\(\)]*)$/|digits:9', 'required')]
        ]);

        $order = new Order();
        $order->ticket_number = $request->ticket_number;
        $order->status = $request->status;
        $order->customer_id = $request->customer_id;
        $order->total_price = $request->total_price;
        $order->total_paid = $request->total_paid;
        $order->total_paid_with_points = $request->total_paid_with_points;
        $order->points_gained = $request->points_gained;
        $order->points_used_to_pay = $request->points_used_to_pay;
        $order->payment_type = $request->payment_type;
        $order->payment_reference = $request->payment_reference;
        //set the current date
        $order->date = date('Y-m-d');
        $order->save();

        return response()->json($order);
    }
}
