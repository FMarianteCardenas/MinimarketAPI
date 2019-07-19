<?php

use App\Minimarket;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinimarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minimarkets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('address');
            $table->string('patent')->unique();
            $table->integer('is_active')->default(Minimarket::IS_ACTIVE);
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minimarkets');
    }
}
