<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=['name','price','stock','deleted_at'];
    protected $hidden = ['pivot'];

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class,ProductCategory::class)->withTimestamps();
    }

    public function cartItem(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CustomerCartItem::class,'product_id','id');
    }
}
