<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\Customer::factory(10)->create();
         \App\Models\Category::factory(2)->create();
         \App\Models\Product::factory(10)->create();
         foreach (Category::all() as $category){
             foreach (Product:: all() as $product)
             ProductCategory::create(['product_id'=>$product->id,'category_id'=>$category->id]);
         }
    }
}
