<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\CustomerCart::class,'customer_cart_id')->constrained();
            $table->foreignIdFor(\App\Models\CartDiscountType::class,'cart_discount_type_id')->constrained();
            $table->decimal('discount_amount',10,2)->default('0.00');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_discounts');
    }
};
