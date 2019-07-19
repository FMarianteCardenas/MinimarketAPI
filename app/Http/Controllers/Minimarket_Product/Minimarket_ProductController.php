<?php

namespace App\Http\Controllers\Minimarket_Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Minimarket;
use App\Product;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Apicontroller;
use App\Http\Resources\MyProducts\ProductResource;
use App\Http\Resources\MyProducts\ProductCollection;

class Minimarket_ProductController extends Apicontroller
{
    public function __construct(){
        //$this->middleware('client.credentials')->only(['index','show']);
        $this->middleware('auth:api');
    }

    public function showProducts(Minimarket $minimarket){
    	/*primera forma*/
       //$collection = $minimarket->products()->paginate(1);
       //return new ProductCollection($collection);
       //return $collection;
       /*primera forma*/
       
       /*$products = $minimarket->products;
       $collection = $this->setCollection($products);
       return new ProductCollection($collection);*/
       //return $this->paginate($collection);
       //dd($minimarket->products());
       /*setea una collection debido a que los datos se obtienen de una tabla pivot y NO de un MODELO directamente en este caso minimarket_product*/
       $collection = $this->setCollection($minimarket->products());

       return new ProductCollection($collection);
    }
}
