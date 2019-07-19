<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Minimarket;

class SuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/*creando el minimercado padre*/
    	Minimarket::create([
    		'name' => 'Super Minimercado',
    		'address' => 'Lago Huillinco #302',
    		'patent' => '0001',
    		'is_active' => Minimarket::IS_ACTIVE
    	]);

    	/*creando un super usuario*/
        User::create([
        	'name' => 'Fermin',
	        'lastname' => 'Mariante',
	        'username' => 'FMariante',
	        'email' => 'fmariantecardenas@gmail.com',
	        'email_verified_at' => new DateTime(),
	        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
	        'remember_token' => str_random(10),
	        'is_admin' => User::IS_ADMIN,
	        'is_active'  => User::IS_ACTIVE,
	        'is_owner' => User::IS_OWNER,
	        'verification_token' => User::generateVerificationToken(),
	        'minimarket_id' => '1'
        ]);
    }
}
