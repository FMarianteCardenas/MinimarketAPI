<?php

namespace App\Http\Controllers\Minimarket_Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Apicontroller;
use App\Minimarket;


class Minimarket_CategoryController extends Apicontroller
{
    /**
	* Permite registrar un producto asociado a un minimarket
	* @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
	*/
	public function storeCategoryToMinimarket(Request $request, $minimarket_id){
		
		$minimarket = Minimarket::findOrFail($minimarket_id);
		/*dd($minimarket);*/
		$reglas = [
		'category_id'=>'required',
		'minimarket_id'=>'required'
		];
		
		$messages = [
		'category_id.required'=>'El campo :attribute es requerido',
		'minimarket_id.required'=>'El campo :attribute es requerido'
		];
		
		$this->validate($request,$reglas,$messages);
		
		$min_cat = DB::table('category_minimarket')->insert([
		'minimarket_id' => $request['minimarket_id'],
		'category_id' => $request['category_id']
		]);
		
		$categories = DB::table('category_minimarket as pivot')->select('c.id','c.name','c.description')
		           ->join('categories as c',function($join)use($request,$minimarket){
					   $join->on('c.id','=','pivot.category_id')
					   ->where('pivot.minimarket_id','=',$minimarket->id);
				   })
				   ->get();
		
		/*return response()->json(['data'=>$product],200);*/
		/*return $this->showOne($product,201);*/
		return $this->showCustomResponse($categories,201);
	}
	
	public function getCategoriesFromMinimarket($minimarket_id){
		$minimarket = Minimarket::findOrFail($minimarket_id);
		
		$categories = $minimarket->categories;
		/*$start = 2;
		$step = 2;
		$end = $start + $step;
		$categories = Minimarket::with(['categories'] => function($q) use($start, $end){
				$q->limit($start, $end);
		})->find($minimarket->id)->categories;*/
		
		/*$categories = DB::table('category_minimarket as pivot')->select('c.id','c.name','c.description')
		           ->join('categories as c',function($join)use($minimarket){
					   $join->on('c.id','=','pivot.category_id')
					   ->where('pivot.minimarket_id','=',$minimarket->id);
				   })
				   ->get();
		
		/*return response()->json(['data'=>$product],200);*/
		/*return $this->showOne($product,201);*/
		return $this->showCustomResponse($categories,201);
	}
}
