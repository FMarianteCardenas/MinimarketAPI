<?php

namespace App\Http\Controllers\Minimarket;

use App\Minimarket;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Apicontroller;

use App\Http\Resources\Minimarket\MinimarketResource;
use App\Http\Resources\Minimarket\MinimarketCollection;

use App\Http\Resources\Minimarket\ProductResource;

class MinimarketController extends Apicontroller
{
     public function __construct(){
        $this->middleware('client.credentials')->only(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new Minimarket;
        $collection = $this->setBasic($model);
		//$collection = User::all();
        return new MinimarketCollection($collection);
        //$minimarkets = Minimarket::all();
		/*return response()->json(['data',$minimarkets]);*/
		
        /*usando el trait que retorna una coleccion*/
	return $this->showAll($minimarkets);		
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
		     'name'=>'required|unique:minimarkets',
			 'address'=>'required',
			 'patent'=>'required|unique:minimarkets'
		];
		
		$messages = [
		     'name.required' => 'El nombre es requerido',
			 'name.unique' => 'El nombre del minimercado ya existe',
			 'address.required' => 'La dirección es requerida',
			 'patent.required' => 'La patente es requerida',
			 'patent.unique' => 'La patente ya se encuentra registrada'
		];
		
		$this->validate($request,$reglas,$messages);

		$minimarket_data = $request->all();
		$minimarket_data['is_active'] = Minimarket::IS_ACTIVE;
		$minimarket = Minimarket::create($minimarket_data);
		/*return response()->json(['data' => $minimarket,'status' => 201, 'response' => 'minimercado creado'],201);*/
		return $this->showOne($minimarket,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Minimarket $minimarket)
    {
		/*$minimarket = Minimarket::findOrFail($id);*/
		/*return response()->json(['data'=>$minimarket],200);*/
        /*$categories = $minimarket->categories()->get();
		$products = $minimarket->products()->get();
		$users = $minimarket->users()->get();
		return response()->json(['data'=>$minimarket,'relationships'=>['users'=>$users,'categories'=>$categories,'products'=>$products]],200);*/
		//return $this->showOne($minimarket);
        MinimarketResource::withoutWrapping();
		return  new MinimarketResource($minimarket);
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
    public function update(Request $request, Minimarket $minimarket)
    {
		/*$minimarket  = Minimarket::findOrFail($id);*/
        $reglas = [
		   'name' => 'unique:minimarkets'
		];
		
		$messages = [
		   'name.unique' => 'EL nombre del minimercado ya existe y no puede ser usado'
		];
		
		$this->validate($request,$reglas,$messages);
		
        if($request->has('name')){
			$minimarket->name = $request->name;
		}
		if($request->has('address')){
			$minimarket->address = $request->address;
		}
		
		if(!$minimarket->isDirty()){
			return response()->json(['error'=>'debe especificar al menos un campo diferente para actualizar','code'=>422],422);
		}
		
		$minimarket->save();
		/*return response()->json(['data'=>$minimarket],200);*/
		return $this->showOne($minimarket,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Minimarket $minimarket)
    {
        /*$minimarket = Minimarket::findOrFail($id);*/
		/*$minimarket->delete();*/
		$minimarket->is_active = 0;
		$minimarket->save();
		/*return response()->json(['data'=>$minimarket],200);*/
		return $this->showOne($minimarket,200);
    }
    
    /**
     * Obtiene un producto de un minimercado por medio del codigo
     * @param int $minimarket Minimarket
     * @param string $code Codigo
     */
    
    public function getProductByCode(Minimarket $minimarket,$code){
        //$minimarket = Minimarket::findOrFail($minimarket_id);
        $product = $minimarket->products->where('code','=',$code)->first();
        //dd($product);
        ProductResource::withoutWrapping();
	    return  new ProductResource($product);
        
    }
    
    /**
     * Obtiene un producto de un minimercado por medio de la Id
     * @param int $minimarket Minimarket
     * @param int $product Producto
     */
    
    public function getProductById(Minimarket $minimarket, Product $product){
        //$minimarket = Minimarket::findOrFail($minimarket_id);
        //$product = Product::findOrFail($product_id);
        
        $product_result = $minimarket->products->where('id','=',$product->id)->first();
        //dd($product_result);
        if($product_result != null){
            ProductResource::withoutWrapping();
            return  new ProductResource($product_result);
        }
        else{
            return $this->errorResponse('El minimercado no tiene registrado el producto',404);
        }
    }
}
