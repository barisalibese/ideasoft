<?php


namespace App\Services;


use App\Models\CartDiscount;
use App\Models\CustomerCart;
use App\Models\CustomerCartItem;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function completeTransaction()
    {
        $cart = $this->getCustomerCart();
        if (empty($cart)) {
            return (new JsonResponse('user cart is not exists', 400))->send();
        }
        $productStockResponse = $this->updateProductStock($cart);
        if (!$productStockResponse['status']) {
            return (new JsonResponse($productStockResponse['product']->id . ' ID Product stock is ' . $productStockResponse['product']->stock, 400))->send();
        }
        $this->updateCustomerRevenue($cart);
        $order = new Order();
        $order->customer_cart_id = $cart->id;
        $order->save();
        $cart->status = 'ordered';
        $cart->save();
        return (new JsonResponse(['message' => 'success'], 200))->send();
    }

    private function getCustomerCart()
    {
        return auth('sanctum')->user()->cart()->where('status', 'ordering')->first();
    }

    private function updateCustomerRevenue($cart)
    {
        $user = auth('sanctum')->user();
        $user->revenue = $user->revenue + ($cart->total_price - $cart->cartDiscounts()->sum('discount_amount'));
        $user->save();
    }

    private function updateProductStock($cart)
    {
        DB::beginTransaction();
        foreach ($cart->cartItems()->get() as $item) {
            $product = $item->product()->first();
            if ($product->stock < $item->quantity) {
                DB::rollBack();
                return ['status' => false, 'product' => $product];
            }
            $product->stock = $product->stock - $item->quantity;
            $product->save();
        }
        DB::commit();
        return ['status' => true];
    }

    public function getCustomerOrders()
    {
        $orders=Order::select('id')->get();
        $orders->map(function ($order) {
            $cart=CustomerCart::where('id',$order->id)->first();
           $order->customer_id=$cart->customer_id;
           $order->items=CustomerCartItem::select('product_id','quantity','unit_price','total_price as total')->where('customer_cart_id',$cart->id)->get();
            $order->total=$cart->total_price;
        });

        return (new JsonResponse(['message' => 'success', 'data' => $orders], 200))->send();
    }
    public function getDiscountsByOrder(){
        $orders=Order::select('id')->get();
        foreach ($orders as $key=>$order){
            $cart=CustomerCart::where('id',$order->id)->first();
            $total=$cart->total_price;
            $totalDiscount=0;
            $order->discounts=CartDiscount::where('customer_cart_id',$cart->id)
                ->select('cart_discount_types.name as discount_reason','discount_amount')
                ->join('cart_discount_types','cart_discount_types.id','=','cart_discounts.cart_discount_type_id')
                ->get();
            if(count($order->discounts)==0){
                unset($orders[$key]);
                continue;
            }
            foreach ($order->discounts as $discount){
                $totalDiscount=$totalDiscount+$discount->discount_amount;
                $total=$total-$discount->discount_amount;
                $discount->subTotal=$total;
            }
            $order->totalDiscount=$totalDiscount;
            $order->discountedTotal=$total;
        }
       $orders= array_values($orders->toArray());
        return (new JsonResponse(['message' => 'success', 'data' => $orders], 200))->send();
    }
}
