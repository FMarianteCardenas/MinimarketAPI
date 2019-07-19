<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MinimarketSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minimarket_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->date('start_date');
            $table->date('expiration_date');
            $table->integer('is_active')->unsigned();
            $table->integer('minimarket_id')->unsigned();
            $table->integer('subscription_id')->unsigned();
            $table->timestamps();

            $table->foreign('minimarket_id')->references('id')->on('minimarkets');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minimarket_subscription');
    }
}
