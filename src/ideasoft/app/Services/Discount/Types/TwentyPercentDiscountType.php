<?php


namespace App\Services\Discount\Types;


use App\Models\CartDiscount;
use App\Models\CartDiscountType;

class TwentyPercentDiscountType implements DiscountTypeInterface
{
    const category=1;
    const countLimit=2;
    public function handle($cart, $type)
    {
        $counter=0;
        $lowestPrice=0;
        $items=$this->getCartItems($cart);
        $discount=$this->getCreatedDiscount($cart,$type);
        if(!empty($items)){
            foreach ($items as $item){
                if($this->checkItemCategory($item)) {
                    if ($counter == 0) {
                        $lowestPrice = $item->unit_price;
                    }
                    else if($lowestPrice>$item->unit_price){
                        $lowestPrice = $item->unit_price;
                    }
                    $counter++;
                }
            }
        }
        $lowestPrice=$lowestPrice*0.2;
        if(!empty($discount) && $counter>=self::countLimit && $lowestPrice!=$discount->discount_amount){
            $discount->discount_amount=$lowestPrice;
            $discount->save();
            return true;
        }
        else if (!empty($discount) && $counter<self::countLimit){
            $discount->delete();
        }
        else if(empty($discount) && $counter>=self::countLimit){
            $discount=new CartDiscount();
            $discount->customer_cart_id=$cart->id;
            $discount->cart_discount_type_id=$this->getDiscountType($type)->id;
            $discount->discount_amount=$lowestPrice;
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
