<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


protected  function getOrderPayload(){
    return ["products"=>[["product_id"=>1, "quantity"=>4]]];
}
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_order_missing_field()
    {
        $response = $this->post('/api/v1/order');

        $response->assertStatus(422);
    }
    protected  function getCurrentStockByProduct($productId){
        return Ingredient::where('product_id',$productId)->first('available_quantity_gram');
    }

    public function test_create_new_order()
    {

//        dd($order);

        $response = $this->post('/api/v1/order',$this->getOrderPayload());


        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
        $this->assertTrue($response->json()['success']);
    }
    public function  test_is_stock_updated(){
        $currentStock = $this->getCurrentStockByProduct(1);

        $response = $this->post('/api/v1/order',$this->getOrderPayload());

        $updatedStock = $this->getCurrentStockByProduct(1);
        $response->assertStatus(200);
        $this->assertNotEquals($currentStock,$updatedStock);
    }
}
