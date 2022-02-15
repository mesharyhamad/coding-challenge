<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string("ingredient_name")->nullable();
            $table->integer('product_id');
            $table->integer('available_quantity_gram');
            $table->integer('main_quantity_gram');
            $table->integer('default_order_gram');
            $table->boolean('was_send_mail')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
     /*   Schema::table('ingredients',function (Blueprint $table){
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
};
