<?php
use App\User;
use App\Product;
use App\Category;
use App\Minimarket;
use Faker\Generator as Faker;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */


// $factory->define(App\Minimarket::class, function (Faker $faker) {
//     return [
//         'name' => $faker->unique()->name,
//         'address' => $faker->word,
//         'patent' => $faker->unique()->word,
//         'is_active'  => $faker->randomElement([User::IS_ACTIVE,User::IS_NOT_ACTIVE])
//     ];
// });

// $factory->define(App\User::class, function (Faker $faker) {
//     return [
//         'name' => $faker->name,
//         'lastname' => 'Mariante',
//         'username' => $faker->unique()->word,
//         'email' => $faker->unique()->safeEmail,
//         'email_verified_at' => new DateTime(),
//         'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
//         'remember_token' => str_random(10),
//         'is_admin' => User::IS_ADMIN,
//         'is_active'  => User::IS_ACTIVE,
//         'is_owner' => User::IS_NOT_OWNER,
//         'verification_token' => User::generateVerificationToken(),
//         'minimarket_id' => Minimarket::all()->random()->id
//     ];
// });



// $factory->define(App\Category::class, function (Faker $faker) {
//     return [
//         'name' => $faker->unique()->word,
//         'description' => $faker->paragraph(1),
//     ];
// });

// $factory->define(App\Product::class, function (Faker $faker) {
//     return [
//         'code' => $faker->unique()->word,
//         'name' => $faker->unique()->word,
//         'description' => $faker->paragraph(1),
		/*'stock'=>0,
		'buy_price'=>0,
		'sale_price'=>0,
        'is_active'  => Product::IS_ACTIVE,*/
        // 'category_id' => Category::all()->random()->id,
		/*'minimarket_id' => Minimarket::all()->random()->id*/
//     ];
// });
