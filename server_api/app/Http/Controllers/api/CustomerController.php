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
            return response()->json(['message' => 'Customer has no orders'], 404);
        }

        return OrderResource::collection($customer->order);
    }
}
