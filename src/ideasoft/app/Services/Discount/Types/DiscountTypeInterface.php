<?php


namespace App\Services\Discount\Types;


use App\Models\CustomerCart;

interface DiscountTypeInterface
{
    public function handle($cart,$type);
}
