<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('lastname');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('is_admin')->default(User::IS_NOT_ADMIN);
            $table->integer('is_active')->default(User::IS_ACTIVE);
            $table->integer('is_owner')->default(User::IS_NOT_OWNER);
            $table->string('verification_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
			$table->softDeletes();
            $table->integer('minimarket_id')->unsigned();


            $table->foreign('minimarket_id')->references('id')->on('minimarkets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
