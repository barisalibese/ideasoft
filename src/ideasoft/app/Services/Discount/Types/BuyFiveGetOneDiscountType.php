<?php


namespace App\Services\Discount\Types;


use App\Models\CartDiscount;
use App\Models\CartDiscountType;

class BuyFiveGetOneDiscountType implements DiscountTypeInterface
{
    const discountAmount=5;
    const category=2;
    public function handle($cart, $type)
    {
        $discountAmount=0;
        $discount=$this->getCreatedDiscount($cart,$type);
        $cartItems=$this->getCartItems($cart);
        if(!empty($cartItems)){
            foreach ($this->getCartItems($cart) as $item){
                if($item->quantity>self::discountAmount && $this->checkItemCategory($item)){
                    $discountAmount=$discountAmount+$item->unit_price;

                }
            }
        }
        if(!empty($discount) && $discount->discount_amount!=$discountAmount && $discountAmount!=0){
             $discount->discount_amount=$cart->total_price*0.1;
            $discount->save();
            return true;
        }
        else if(!empty($discount) && $discountAmount==0){
            $discount->delete();
            return true;
        }
        else if(empty($discount) && $discountAmount!=0){
            $discount=new CartDiscount();
            $discount->customer_cart_id=$cart->id;
            $discount->cart_discount_type_id=$this->getDiscountType($type)->id;
            $discount->discount_amount=$discountAmount;
            $discount->save();
            return true;
        }
        return true;
    }

    private function getCreatedDiscount($cart,$type){
        return CartDiscount::where('customer_cart_id',$cart->id)->where('cart_discount_type_id',$this->getDiscountType($type)->id)->first();
    }
    private function getDiscountType($type){
        return CartDiscountType::where('name',$type)->first();
    }
    private function getCartItems($cart){
        return $cart->cartItems()->get();
    }
    private function checkItemCategory($item){
        return $item->product()->first()->categories()->where('categories.id',self::category)->exists();
    }
}
