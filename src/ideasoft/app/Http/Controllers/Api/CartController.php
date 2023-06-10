<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\DeleteRequest;
use App\Http\Requests\Cart\StoreRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function get(){
        return (new CartService(true))->getCart();
    }
    public function addToCart(StoreRequest $request,CartService $cartService){
        return $cartService->createOrUpdateCart($request);
    }

    public function deleteCart(DeleteRequest $request){
        return (new CartService(true))->deleteItemFromCart($request);
    }
}
