<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CustomerCart;
use App\Models\Product;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomerCart::class,'customer_cart_id')->constrained();
            $table->foreignIdFor(Product::class,'product_id')->constrained();
            $table->decimal('unit_price',10,2)->default('0.00');
            $table->integer('quantity')->default(0);
            $table->decimal('total_price',10,2)->default('0.00');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_cart_items');
    }
};
