<?php

namespace App\Http\Controllers\api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class CustomerController extends Controller
{

    public function showOrders(Customer $customer)
    {
        if($customer == null){
            return response()->json(['message' => 'Customer not found'], 404);
        }
        if ($customer->order == null){
            return response()->json();
        }

        return OrderResource::collection($customer->order);
    }

    public function getTotalCustomersMonthly() {
        $customers = Customer::all();

        $total = 0;
        $date = date('Y-m-d');
        $date = strtotime($date);
        $date = strtotime("-30 day", $date);
        $date = date('Y-m-d', $date);

        foreach ($customers as $customer) {
            if ($customer->created_at >= $date) {
                $total++;
            }
        }
        return $total;
    }
}
