<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    CustomerController,
    OrderController,
    ProductController,
    CategoryController,
    CartController
};
Route::get('customer',[CustomerController::class,'index']);
Route::post('customer/register', [CustomerController::class, 'register']);
Route::post('customer/login', [CustomerController::class, 'login']);
Route::resource('product',ProductController::class);
Route::resource('category',CategoryController::class);
Route::get('order',[OrderController::class,'getOrders']);
Route::get('order-discounts',[OrderController::class,'getDiscounts']);

Route::middleware(['auth'])->group(function () {
    Route::post('order',[OrderController::class,'complete']);
    Route::post('cart/add',[CartController::class,'addToCart']);
    Route::post('cart/delete',[CartController::class,'deleteCart']);

});

