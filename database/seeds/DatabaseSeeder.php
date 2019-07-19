<?php

use App\User;
use App\Product;
use App\Category;
use App\Minimarket;
use App\Sale;
use App\Subscription;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Minimarket::truncate();
        Category::truncate();
        Product::truncate();
        Subscription::truncate();
        Sale::truncate();

        /*evitar que se ejecuten eventos cuando ejecutamos el seeder*/
        User::flushEventListeners();
        Minimarket::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Subscription::flushEventListeners();
        Sale::flushEventListeners();


        DB::table('category_minimarket')->truncate();
		DB::table('minimarket_product')->truncate();
		DB::table('sale_product')->truncate();
        DB::table('minimarket_subscription')->truncate();
        
        $this->call('Roles');
        $this->call('SuperAdmin');
        $this->call('Categories');
        $this->call('ProductWithoutCode');



        // factory(Minimarket::class,20)->create();
        // factory(User::class,20)->create();
        // factory(Category::class,20)->create();
        // factory(Product::class,20)->create();
    }
}
