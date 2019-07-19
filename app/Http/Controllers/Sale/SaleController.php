<?php

namespace App\Http\Controllers\Sale;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Apicontroller;
use App\Sale;
use App\Product;
use App\Http\Resources\Sale\SaleResource;
use App\Http\Resources\Sale\SaleCollection;

class SaleController extends Apicontroller
{
	 public function __construct(){
        //$this->middleware('client.credentials')->only(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$model = new Sale();
    	$collection = $this->setModel($model);
    	return new SaleCollection($collection);
        //$sales = Sale::all();
		/*return response()->json(['data'=>$sales],200);*/
		
		/*usando el trait que retorna una coleccion*/
		//return $this->showAll($sales);
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
		  'total_sale'=>'required',
		  'minimarket_id'=>'required',
		  'user_seller_id'=>'required'
		];
		
		$this->validate($request,$reglas);
		
		$sale_data = $request->all();
		$sale = Sale::Create($sale_data);
		
		/*return response()->json(['data' => $sale,'status' => 201, 'response' => 'venta registrada'],201);*/
		return $this->showOne($sale,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //dd($sale);
        /*$sale = Sale::findOrFail($id);*/
		/*$products = DB::table('sale_product')
			->select('sale_product.sale_id as numero_venta','sale_product.product_id as id_producto','sale_product.quantity as cantidad','p.name as nombre_producto', 'p.code as codigo')
            
		    ->join('sales as s', function ($join) use($sale)
					{
						$join->on('s.id', '=', 'sale_product.sale_id')
						     ->where('s.id','=',$sale->id);
					})
		    ->join('products as p',function ($join){
				$join->on('p.id','=','sale_product.product_id');
			})
            ->get();
			
			return response()->json(['data'=>$sale,'relationships'=>['products'=>$products]],200);*/
        SaleResource::withoutWrapping();
        return new SaleResource($sale);
	//return $this->showOne($sale);
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
    public function update(Request $request, $id)
    {
        //
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
     * Asocia los productos a la venta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saleProduct(Request $request, $id)
    {
		if(isset($request->data)){
			/*sirve para indicar una transaccion*/
			DB::beginTransaction();
			$reglas = [
		        'quantity' => 'required',
				'product_id' => 'required',
				'sale_id' => 'required'
		    ];
			
			$messages = [
                'quantity.required' => 'La Cantidad es Requerida',
				'product_id.required' => 'El Producto es Requerido',
				'sale_id.required' => 'La id de la venta es requerida'
            ];
			
			$sale = Sale::findOrFail($id);
			
			foreach($request->data as $data){
				$validator = Validator::make($data, $reglas, $messages);
				if ($validator->fails()) {
                    DB::rollback();
					$errorString = implode(",",$validator->messages()->all());
                    /*return response()->json(['error'=> $errorString],401);*/
					return $this->errorResponse($errorString,401);
                }
				else{
					DB::table('sale_product')->insert([
						'sale_id' => $sale->id,
						'product_id' => $data['product_id'],
						'quantity' => $data['quantity']
					]);
				}
				//dd("Fila: ",$data);
			}
			DB::commit();
			
			$products = DB::table('sale_product')
			->select('sale_product.sale_id as numero_venta','sale_product.product_id as id_producto','sale_product.quantity as cantidad','p.name as nombre_producto', 'p.code as codigo')
            
		    ->join('sales as s', function ($join) use($sale)
					{
						$join->on('s.id', '=', 'sale_product.sale_id')
						     ->where('s.id','=',$sale->id);
					})
		    ->join('products as p',function ($join){
				$join->on('p.id','=','sale_product.product_id');
			})
            ->get();
			
			return response()->json(['data'=>$sale,'relationships'=>['products'=>$products]],200);
			
		}
		/*$reglas = [
		        'quantity' => 'required|number',
				'product_id' => 'required|number'
		];*/
		
		/*$this->validate($request,$reglas);*/
		
        
		/*$product = Product::findOrFail($request->product_id);*/
		/*$products = json_decode($request->getContent());
		$products2 = parse_str($request->getContent(),$data);
		dd($products2);
		/*foreach ($products as $p) {
		   /*DB::table('sale_product')->insert([
					'sale_id' => $id,
					'sale_product' => $p->product_id,
					'quantity' => $p->quantity
				]);*/
				/*dd($p);
		}*/
		
		
		
		
    }
	
	public function getProductsFromSale($id){
	   $sale = Sale::findOrFail($id);
	   $products = $sale->products()->with('minimarkets')->get();
	   
	   dd($products);
	   return $this->showAll($products);
	}
}
