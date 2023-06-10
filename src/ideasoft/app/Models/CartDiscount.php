<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartDiscount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=['customer_cart_id','cart_discount_type_id','discount_amount'];

    public function cart(){
        $this->belongsTo(CartDiscount::class);
    }
}
