<?php

use Carbon\Traits\Rounding;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\FilesController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\Order_ItemController;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'create']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('users/me', [UserController::class, 'show_me']);
    Route::get('users', [UserController::class, 'index']);

    Route::get('users/{user}', [UserController::class, 'show'])
        ->middleware('can:view,user');
    Route::patch('users/{user}', [UserController::class, 'update'])
      ->middleware('can:update,user');
    Route::patch('users/{user}/password', [UserController::class, 'update_password'])
        ->middleware('can:updatePassword,user');

    Route::get('users/{user}/tasks', [TaskController::class, 'getTasksOfUser']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
    Route::put('tasks/{task}', [TaskController::class, 'update']);
    Route::patch('tasks/{task}/completed', [TaskController::class, 'update_completed']);

    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{project}', [ProjectController::class, 'show']);
    Route::get('projects/{project}/tasks', [ProjectController::class, 'showWithTasks']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::delete('projects/{project}', [ProjectController::class, 'destroy']);
    Route::put('projects/{project}', [ProjectController::class, 'update']);
    Route::get('users/{user}/projects', [ProjectController::class, 'getProjectsOfUser']);
    Route::get('users/{user}/projects/inprogress', [ProjectController::class, 'getProjectsInProgressOfUser']);

    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});

Route::get('customerpayment/{customer}', [CustomerController::class, 'getCustomerPayment']);
Route::get('customerreference/{customer}', [CustomerController::class, 'getCustomerReference']);

Route::get('products', [ProductController::class, 'getProducts']);
Route::get('products/{type}', [ProductController::class, 'getProductByType']);
Route::get('products/order/items', [ProductController::class, 'getProductsByOrderItemStatus']);

Route::post('products', [ProductController::class, 'store']);

Route::get('product/{id}', [ProductController::class, 'index']);
Route::put('product/{id}', [ProductController::class, 'update']);

Route::get('orders', [OrderController::class, 'index']);
Route::post('orders', [OrderController::class, 'store']);

Route::get('orderitems', [Order_ItemController::class, 'index']);
Route::get('orderitems/{order_item}', [Order_ItemController::class, 'show']);
Route::post('orderitems', [Order_ItemController::class, 'store']);
Route::patch('orderitems/{order_item}/status', [Order_ItemController::class, 'updateStatus']);

Route::post('employee', [UserController::class, 'createEmployee']);

//Route::get('users', [UserController::class, 'index']);
//Route::patch('users/{user}', [UserController::class, 'update']);

Route::get('customer/{customer}/orders', [CustomerController::class, 'showOrders']);

Route::post('files',[FilesController::class,'store']);
