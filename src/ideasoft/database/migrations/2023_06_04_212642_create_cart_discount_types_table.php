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
        Schema::create('cart_discount_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        $data=[
            ['name'=>'10_PERCENT_OVER_1000'],
            ['name'=>'20_PERCENT_DISCOUNT'],
            ['name'=>'BUY_5_GET_1'],
        ];
        \App\Models\CartDiscountType::insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_discount_types');
    }
};
