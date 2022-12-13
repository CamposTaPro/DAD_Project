<?php

namespace App\Http\Controllers\api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class CustomerController extends Controller
{
    public function getCustomerPayment(Customer $customer){
        return $customer->default_payment_type;
    }

    public function getCustomerReference(Customer $customer){
        return $customer->default_payment_reference;
    }

    public function showOrders(Request $request, Customer $customer)
    {
        //return $customer->order;
        return OrderResource::collection($customer->order);
        //return response()->json($customer->order);

    }
}
