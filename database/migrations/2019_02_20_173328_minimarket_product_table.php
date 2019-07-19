<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MinimarketProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('minimarket_product', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('buy_price')->unsigned();
            $table->integer('sale_price')->unsigned();
            $table->integer('stock')->unsigned();
			$table->integer('is_active')->unsigned();
			$table->integer('minimarket_id')->unsigned();
			$table->integer('product_id')->unsigned();

            $table->foreign('minimarket_id')->references('id')->on('minimarkets');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minimarket_product');
    }
}
