<?php

namespace App\Http\Controllers\Product;


use Illuminate\Support\Facades\DB;
use App\Product;
//use App\Minimarket;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Apicontroller;

class ProductController extends Apicontroller
{
	 public function __construct(){
        //$this->middleware('client.credentials')->only(['index','show']);
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//dd("SI");
    	$model = new Product;
    	$collection = $this->setModel($model);
		return new ProductCollection($collection);
        //$products = Product::all();
		
		/*usando el trait que retorna una coleccion*/
		//return $this->showAll($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Crea un nuevo producto (disponible para todas las tiendas).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$inputs = $request['data']['attributes'];
        $reglas = [
		'code' => 'required|unique:products',
		'name' => 'required',
		'description' => 'required',
		'category_id' => 'required'
		];
		
		$messages = [
		    'code.unique' => 'El codigo de producto debe ser único',
			'code.required' => 'El codigo del producto es requerido',
			'name.required' => 'El nombre del producto es requerido',
			'description.required' => 'La descripción del producto es requerida',
			'category_id.required' => 'Debe proporcionar una id de categoria al producto'
		];

		$validator = Validator::make($inputs,$reglas,$messages);
		//$this->validate($inputs,$reglas,$messages);

		if ($validator->fails()) {
                return $this->errorResponse($this->validationErrorsToString($validator->errors()), 403);
        }
		
		//$product_data = $request->all();
		$product = Product::create($inputs);
		return new ProductResource($product);
		/*return response()->json(['data' => $product,'status' => 201, 'response' => 'Producto Creado'],201);*/
		//return $this->showOne($product,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Product $product)
    {
        /*$product = Product::findOrFail($id);*/
		/*return response()->json(['data' => $product],200);*/
		$this->showOne($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
		$product->fill($request->intersect([
		    'name',
			'description',
			'category_id'
		]));
		
		/*si la instancia no ha cambiado returna un error*/
		if($product->isClean()){
			return $this->errorResponse('debe de modificar al menos un campo',422);
		}
        /*if($request->has('name')){
			$product->name = $request->name;
		}
		if($request->has('description')){
			$product->description = $request->description;
		}
		
		if($request->has('category_id')){
			$product->category_id = $request->category_id;
		}*/
		
		$product->save();
		
		return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	/**
     * Obtiene un producto especifico de una tienda por medio de la id del producto.
     *
     * @param  int  $id_min
	 * @param  int  $id_pro
     * @return \Illuminate\Http\Response
     */
    public function productMinimarket($min_id, $prod_id)
    {
		$minimarket = Minimarket::findOrFail($min_id);
		 /*forma 1 de obtener un producto*/
		 $result = DB::table('products')
		 ->select('products.code as code','products.name as name','mp.sale_price as final_price','mp.stock as stock','minimarkets.name as super')
        ->join('minimarket_product as mp', function ($join) use($minimarket,$prod_id) 
		{
            $join->on('products.id', '=', 'mp.product_id')
			     ->where('mp.minimarket_id', '=', $minimarket->id)
                 ->where('mp.product_id', '=', $prod_id);
        })
		->join('minimarkets', function ($join) use ($minimarket)
		{

			$join->on('minimarkets.id','=','mp.minimarket_id')
		->where('minimarkets.id','=',$minimarket->id);
		})
        ->get();
		
		/*forma 2*/
		 $p = Minimarket::find($minimarket->id)->products()->where('minimarket_product.product_id', $prod_id)->get();
		 
		 /*forma 3 trae solo los datos de la tabla pivot*/
		$product_detail = DB::table('minimarket_product')
		->Where(function ($query)use ($minimarket,$prod_id) {
                $query->where('product_id', '=', $prod_id)
                      ->where('minimarket_id', '=', $minimarket->id);
            })
            ->get();
		return response()->json(['data'=>$result],200);
    }
	
	/**
     * Obtiene un producto especifico de una tienda a través del codigo de producto.
     *
     * @param  int  $min_id
	 * @param  int  $prod_code
     * @return \Illuminate\Http\Response
     */
	public function productCode($min_id,$prod_code){
		$minimarket = Minimarket::findOrFail($min_id);
		$product = DB::Table('minimarket_product as m_p')
		           ->select('p.code as codigo','p.name as nombre','m_p.sale_price as precio_venta')
				   ->join('products as p', function ($join) use ($min_id,$prod_code)
				   {
					   $join->on('p.id','=','m_p.product_id')
					   ->where('m_p.minimarket_id','=',$min_id)
					   ->where ('p.code','=',$prod_code);
				   })
				   /*->where('p.code','=',$prod_code)
				   ->where('p.minimarket_id','=',$minimarket->id)*/
				   ->get();
		//dd($product);
	    if($product->count() > 0){
			/*return response()->json(['data'=>$product,'relationships'=>['minimarket'=>$minimarket]],200);*/
			return $this->showOne($product,200);
		}
		else{
			/*return response()->json(['message'=>'Producto no Encontrado','status'=>404],404);*/
			return $this->errorResponse('Producto no Encontrado',404);
		}
	    
	}
	
	/**
	* Permite registrar un producto asociado a un minimarket
	* @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
	*/
	public function storeProductToMinimarket(Request $request, $minimarket_id){
		
		$minimarket = Minimarket::findOrFail($minimarket_id);
		/*dd($minimarket);*/
		$reglas = [
		'buy_price'=>'required',
		'sale_price'=>'required',
		'stock' => 'required',
		'product_id'=>'required',
		'minimarket_id'=>'required'
		];
		
		$messages = [
		'buy_price.required'=>'El campo :attribute es requerido',
		'sale_price.required'=>'El campo :attribute es requerido',
		'stock.required' => 'El campo :attribute es requerido',
		'product_id.required'=>'El campo :attribute es requerido',
		'minimarket_id.required'=>'El campo :attribute es requerido'
		];
		
		$this->validate($request,$reglas,$messages);
		
		$product = DB::table('minimarket_product')->insert([
		'buy_price' => $request['buy_price'],
		'sale_price' => $request['sale_price'],
		'stock'  => $request['stock'],
		'product_id'  => $request['product_id'],
		'minimarket_id'  => $minimarket->id,
		'is_active' => Product::IS_ACTIVE
		]);
		
		$product = DB::table('minimarket_product as pivot')->select('pivot.buy_price as precio_compra','pivot.sale_price as precio_venta','pivot.stock as cantidad','p.code as codigo','p.name as producto','m.name as nombre_minimercado')
		           ->join('products as p',function($join)use($request,$minimarket){
					   $join->on('p.id','=','pivot.product_id')
					   ->where('p.id','=',$request['product_id'])
					   ->where('pivot.minimarket_id','=',$minimarket->id);
				   })
				   ->join('minimarkets as m',function($join)use($minimarket){
					   $join->on('m.id','=','pivot.minimarket_id')
					        ->where('m.id','=',$minimarket->id);
				   })
				   ->get();
		
		/*return response()->json(['data'=>$product],200);*/
		/*return $this->showOne($product,201);*/
		return $this->showCustomResponse($product,201);
	}
}
