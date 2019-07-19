<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description');
			/*$table->integer('stock')->unsigned();
			$table->integer('buy_price')->unsigned();
			$table->integer('sale_price')->unsigned();
            $table->integer('is_active')->unsigned();*/
            $table->integer('category_id')->unsigned();
			/*$table->integer('minimarket_id')->unsigned();*/
			
            $table->timestamps();
			$table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories');
			/*$table->foreign('minimarket_id')->references('id')->on('minimarkets');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
