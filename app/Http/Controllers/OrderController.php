<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Mail\NotificationMail;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use function Symfony\Component\Translation\t;

class OrderController extends Controller
{
//    Default order gram of Ingredients





    /**
     * Below use DI for all object
     *
     * @var Product
     *  @var Order
     *  @var Ingredient
     */

    protected  $products;
    protected  $orders;
    protected  $ingredients;
    protected  $request;
    protected  $ingredientQuery;
    protected  $product_ids;

    /**
     * Create a new  instances.
     *
     * @param Product $products
     * @param Order $orders
     * @param Ingredient $ingredients
     * @param OrderRequest $request
     */

    public function __construct(Product $products,Order $orders,Ingredient $ingredients,OrderRequest $request){
        $this->products=$products;
        $this->orders=$orders;
        $this->ingredients=$ingredients;
        $this->request=$request;
    }
//    This function will update the Ingredient by product id
    function updateIngredientNameById(){
//        $this->ingredientQuery->update(['available_quantity_gram']);
    }
    function updateQuantity(){

        $products= $this->request->get('products');
        foreach ($products as $product){
            $ingredient = $this->ingredients->find($product['product_id']);
            $result =$ingredient->available_quantity_gram - ($ingredient->default_order_gram * $product['quantity']);
            if($result < 0){
                abort(404,'Not enough stock');
            }
            $ingredient->available_quantity_gram = $result;
            $ingredient->save();

        }

    }

    function checkIngredientStock(){
        //list ids to use it below query

        $this->product_ids = Arr::pluck($this->request->get('products'), 'product_id');

        //find the the level of stock Ingredients reaching of 50%
        $this->ingredientQuery = $this->ingredients->whereIn('product_id',$this->product_ids)->where('was_send_mail',false)
            ->whereRaw( '100 - (((  main_quantity_gram - available_quantity_gram)/main_quantity_gram)*100 ) <= 50')
            ->whereColumn('available_quantity_gram','<','main_quantity_gram');
        //get the ingredient_name as array

        $lowIngredient=  $this->ingredientQuery->get()->pluck('ingredient_name');


        if(count($lowIngredient)>0){
            //convert array of ingredient_name to string
            $lowIngredientStock = collect($lowIngredient)->implode(' and ');
            $this->sendEmail($lowIngredientStock);

            // update the  was_send_mail after sending email
            $this->ingredientQuery->update(['was_send_mail'=>true]);
        }

    }
//    this function to send Notification Mail if needed
    function sendEmail($lowIngredientStock){

        $details = [
            'title' => 'Ingredients stock level',
            'body' => "the ingredients stock level of $lowIngredientStock reaches 50%"
        ];

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new NotificationMail($details));
    }
    function saveOrder(){
        $this->orders->product_ids=json_encode($this->product_ids);
        try {
            $this->orders->save();
        }
        catch (\Exception $exception){
            abort(500,"Server error");
        }

        return $this->orders->id;

    }

    function createOrder(){
        $this->updateQuantity();
         $this->checkIngredientStock();
        return response()->json(['success'=>true,'message' => 'order has been created successfully','data'=>['order_id'=> $this->saveOrder()]], 200);


    }
}
