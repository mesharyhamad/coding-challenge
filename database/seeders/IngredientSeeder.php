<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $product_id=$this->getProductIdByName("Burger");
        Ingredient::create([
            "ingredient_name"=>"Beef",
            "product_id"=>$product_id,
            "available_quantity_gram"=> 20000,
            "main_quantity_gram"=>20000,
            "default_order_gram"=>150,

        ]);
        Ingredient::create([
            "ingredient_name"=>"Cheese",
            "product_id"=>$product_id,
            "available_quantity_gram"=> 5000,
            "main_quantity_gram"=>5000,
            "default_order_gram"=>30,

        ]);
        Ingredient::create([
            "ingredient_name"=>"Onion",
            "product_id"=>$product_id,
            "available_quantity_gram"=> 1000,
            "main_quantity_gram"=>1000,
            "default_order_gram"=>20,

        ]);
    }
    private function getProductIdByName($productName){
         return Product::where('product_name','=',$productName)->first('id')->id ?? null;
    }
}
