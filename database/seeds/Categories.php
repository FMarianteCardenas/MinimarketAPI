<?php

use Illuminate\Database\Seeder;
use App\Category;

class Categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
        	'name' => 'Abarrotes',
        	'description' => 'Alimentos no perecibles'
        ]);

        Category::create([
        	'name' => 'Aseo Personal',
        	'description' => 'Productos de higiene personal'
        ]);

        Category::create([
        	'name' => 'Aseo del Hogar y Limpieza',
        	'description' => 'Productos de limpieza hogareña'
        ]);

        Category::create([
        	'name' => 'Bebidas y Jugos',
        	'description' => 'Productos líquidos'
        ]);

        Category::create([
        	'name' => 'Carnes',
        	'description' => 'Productos crudo derivado de animales comestibles'
        ]);

        Category::create([
        	'name' => 'Cecinas y Quesos',
        	'description' => 'Embutidos y derivados de la leche'
        ]);

        Category::create([
        	'name' => 'Cigarros',
        	'description' => 'Cigarros con tabáco'
        ]);

        Category::create([
        	'name' => 'Frutas y Verduras',
        	'description' => 'Productos alimenticios naturales'
        ]);

        Category::create([
        	'name' => 'Galletas y Snacks',
        	'description' => 'Productos envasados (bocadillos)'
        ]);

        Category::create([
        	'name' => 'Lácteos',
        	'description' => 'Derivados de la leche'
        ]);

        Category::create([
        	'name' => 'Panadería y Dulces',
        	'description' => 'Productos hechos a base de masas'
        ]);

        Category::create([
        	'name' => 'Vinos y Licores',
        	'description' => 'Productos líquidos con alcohol'
        ]);

    }
}
