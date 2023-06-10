<?php


namespace App\Services\Discount\Types;


use App\Models\CartDiscount;
use App\Models\CartDiscountType;

class TenPercentDiscountType implements DiscountTypeInterface
{
    const discountLimit=1000;
    public function handle($cart,$type)
    {
        $discount=$this->getCreatedDiscount($cart,$type);
        if(!empty($discount)){
            if($cart->total_price>=self::discountLimit){
                $discount->discount_amount=$cart->total_price*0.1;
                $discount->save();
                return true;
            }
            $discount->delete();
            return true;
       }
        else if($cart->total_price>=self::discountLimit){
            $discount=new CartDiscount();
            $discount->customer_cart_id=$cart->id;
            $discount->cart_discount_type_id=$this->getDiscountType($type)->id;
            $discount->discount_amount=$cart->total_price*0.1;
            $discount->save();
        }
        return true;
    }

    private function getCreatedDiscount($cart,$type){
        return CartDiscount::where('customer_cart_id',$cart->id)->where('cart_discount_type_id',$this->getDiscountType($type)->id)->first();
    }
    private function getDiscountType($type){
        return CartDiscountType::where('name',$type)->first();
    }
}
