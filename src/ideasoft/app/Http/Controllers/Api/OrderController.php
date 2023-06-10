<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function complete(OrderService $orderService): \Illuminate\Http\JsonResponse
    {
        return $orderService->completeTransaction();
    }
    public function getOrders(OrderService $orderService): \Illuminate\Http\JsonResponse
    {
        return $orderService->getCustomerOrders();
    }
    public function getDiscounts(OrderService $orderService){
        return $orderService->getDiscountsByOrder();
    }
}
