<?php

namespace App\Http\Controllers\api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function getCustomerPayment(Customer $customer){
        return $customer->default_payment_type;
    }

    public function getCustomerReference(Customer $customer){
        return $customer->default_payment_reference;
    }
}
