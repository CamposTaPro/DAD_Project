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
    Route::patch('users/{user}/editblocked', [UserController::class, 'updateBlocked']);

    Route::get('users/{user}', [UserController::class, 'show'])
        ->middleware('can:view,user');
    Route::patch('users/{user}', [UserController::class, 'update'])
      ->middleware('can:update,user');
    Route::patch('users/{user}/password', [UserController::class, 'update_password'])
        ->middleware('can:updatePassword,user');


    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::post('employee', [UserController::class, 'createEmployee']);
    Route::patch('users/{users}/editpoints', [UserController::class, 'updatePoints']);
    Route::get('employees', [UserController::class, 'showEmployes']);

    Route::get('order/pending', [OrderController::class, 'getOrderPending']);

    Route::get('orders/{customer}/points', [OrderController::class, 'getPointsCustomerMonthly']);
    Route::get('orders/{customer}/pointstotal', [OrderController::class, 'getPointsCustomerTotal']);
    Route::get('orders/{customer}/moneyspentmonth', [OrderController::class, 'getMoneySpentMonthly']);
    Route::get('orders/{customer}/moneysaved', [OrderController::class, 'getMoneySavedFromPointsMonthly']);
    Route::get('orders/{customer}/month', [OrderController::class, 'getOrdersMonthly']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);

    Route::get('orderitems_hotdishes', [Order_ItemController::class, 'show_hot_dish']);
    Route::get('orderitems_preparing', [Order_ItemController::class, 'show_preparing']);
    Route::get('orderitems_preparationby', [Order_ItemController::class, 'show_my_preparation']);
    Route::patch('orderitems/{order_item}/status', [Order_ItemController::class, 'updateStatus']);

    Route::get('customer/{customer}/orders', [CustomerController::class, 'showOrders']);


    Route::delete('products/{product}', [ProductController::class, 'destroy']); //TODO - para isto já tenho permissão ??? (tem alguma coisa a haver com o middleware de autenticação)

});
Route::get('verify/{user}',[UserController::class,'verifyEmail']);

Route::get('products', [ProductController::class, 'getProducts']);
Route::get('products/{type}', [ProductController::class, 'getProductByType']);

Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{status}', [OrderController::class, 'getOrderByStatus']);

Route::post('orderitems', [Order_ItemController::class, 'store']);

Route::get('product/{id}', [ProductController::class, 'index']); //TODO - dá Unauthenticated ao manager estando dentro do middleware (tem alguma coisa a haver com o middleware de autenticação)
Route::patch('product/{id}', [ProductController::class, 'update']); //TODO (tem alguma coisa a haver com o middleware de autenticação)
Route::post('products', [ProductController::class, 'store']); //TODO (tem alguma coisa a haver com o middleware de autenticação)

Route::get('orders-ticket', [OrderController::class, 'getTicketNumber']);

Route::get('orders/totalmonth', [OrderController::class, 'getOrdersfromMonthly']);
Route::get('employees/totalmonth', [UserController::class, 'getTotalEmployeesMonthly']);
Route::get('customers/totalmonth', [CustomerController::class, 'getTotalCustomersMonthly']);
