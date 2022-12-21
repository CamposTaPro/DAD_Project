<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Customer;
use App\Notifications\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\UpdateUserPasswordRequest;


class UserController extends Controller
{

    public function destroy(int $id)
    {
        if ($id == null) {
            return response()->json(['message' => 'Id null'], 422);
        }

        $user = User::find($id);

        if ($user == null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json($user);
    }

    public function verifyEmail(User $user)
    {

        if ($user == null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->email_verified_at != null) {
            return response()->json(['message' => 'Email already verified'], 409);
        }

        $user->email_verified_at = now();
        $user->save();
        return Redirect::to('http://127.0.0.1:5173/');
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'nif' => 'required|numeric|unique:customers',
            'phone' => 'required|numeric|unique:customers',
            'default_payment_type' => 'required|string|in:VISA,PAYPAL,MBWAY',
            'default_payment_reference' => [Rule::when($request->default_payment_type == 'VISA', 'required|numeric|digits:16', 'required'), Rule::when($request->default_payment_type == 'PAYPAL', 'required|email', 'required'), Rule::when($request->default_payment_type == 'MBWAY', 'required|numeric|doesnt_start_with:0|regex:/^([0-9\s\-\+\(\)]*)$/|digits:9', 'required')]

        ]);

        $user = new User();
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

        Notification::send($user, new email());
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'customer' => $customer
        ], 201);
    }
    public function index()
    {
        return UserResource::collection(User::paginate(20));
    }

    public function showEmployes()
    {
        return UserResource::collection(User::where('type','LIKE','EM')->orWhere('type','LIKE','EC')->orWhere('type','LIKE','ED')->paginate(10));
    }

    public function show(User $user)
    {
        if ($user == null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        if ($user == null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {

            $customer = Customer::where('user_id', $user->id)->first();

            if ($customer == null) {
                return response()->json(['message' => 'Customer not found'], 404);
            }

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
            if ($customer) {
                $customer->save();
            }

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        if ($user == null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = bcrypt($request->validated()['password']);
        $user->save();
        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        return new UserResource($request->user());
    }
    public function createEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'type' => 'required|string|in:EC,ED',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = $request->type;
        $user->save();

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    public function updateBlocked($id)
    {
        if ($id == null) {
            return response()->json(['message' => 'Id null'], 422);
        }

        $user = User::find($id);

        if ($user == null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->blocked = !$user->blocked;
        $user->save();
        return response()->json([
            'message' => 'User blocked successfully',
            'user' => $user
        ], 200);
    }

    public function updatePoints($id, Request $request)
    {

        if ($id == null) {
            return response()->json(['message' => 'Id null'], 422);
        }

        $user = User::find($id);

        if ($user == null) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $customer = Customer::where('user_id', $user->id)->first();

        if ($customer == null) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->points = $customer->points + $request->points;
        $customer->save();
        return response()->json([
            'message' => 'User points updated successfully',
            'user' => $user
        ], 200);
    }

    public function getTotalEmployeesMonthly() {
        $users = User::where('type', 'EC')->orWhere('type', 'ED')->get();

        $total = 0;
        $date = date('Y-m-d');
        $date = strtotime($date);
        $date = strtotime("-30 day", $date);
        $date = date('Y-m-d', $date);

        foreach ($users as $user) {
            if ($user->created_at >= $date) {
                $total++;
            }
        }

        return $total;
    }
}
