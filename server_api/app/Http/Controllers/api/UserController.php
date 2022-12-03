<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;


class UserController extends Controller
{
    public function create(Request $request)
    {
        $user= new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 'C';
        $user->save();

        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->nif = $request->nif;
        $customer->phone = $request->phone;
        $customer->default_payment_type = $request->default_payment_type;
        $customer->default_payment_reference = $request->default_payment_reference;
        $customer->points = 0;
        $customer->save();

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'customer' => $customer
        ], 201);

    }
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        try {

            $customer = Customer::where('user_id', $user->id)->first();
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('nif')) {
                $customer->nif = $request->nif;
            }
            if ($request->has('phone')) {
                $customer->phone = $request->phone;
            }
            if ($request->has('default_payment_reference')) {
                $customer->default_payment_reference = $request->default_payment_reference;
            }
            $user->save();
            if($customer){
                $customer->save();
            }

            return new UserResource($user);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        $user->password = bcrypt($request->validated()['password']);
        $user->save();
        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        return new UserResource($request->user());
    }
}
