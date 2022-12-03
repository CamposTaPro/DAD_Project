<?php

namespace App\Http\Controllers\api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::all();

        return response()->json($orders);
    }

    public function store(Request $request) {
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
