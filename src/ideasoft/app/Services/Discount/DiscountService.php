<?php


namespace App\Services\Discount;


use App\Services\Discount\Types\BuyFiveGetOneDiscountType;
use App\Services\Discount\Types\TenPercentDiscountType;
use App\Services\Discount\Types\TwentyPercentDiscountType;

class DiscountService
{
    const Discounts=[
        '10_PERCENT_OVER_1000'=>TenPercentDiscountType::class,
        'BUY_5_GET_1'=>BuyFiveGetOneDiscountType::class,
        '20_PERCENT_DISCOUNT'=>TwentyPercentDiscountType::class
    ];

    public function build($cart){
         foreach (self::Discounts as $type=>$discountTemplate){
            $this->process($discountTemplate,$cart,$type);
         }
    }

    private function process($discountTemplate,$cart,$type){
        return (new $discountTemplate())->handle($cart,$type);
    }
}
