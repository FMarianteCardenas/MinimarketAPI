<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Apicontroller;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryCollection;

class CategoryController extends Apicontroller
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
        //$categories = Category::all();
        /*return $categories;*/
		
		/*usando el trait que retorna una coleccion*/
		//return $this->showAll($categories);
        $model = new Category;
        $collection = $this->setModel($model);
        return new CategoryCollection($collection);
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
		    'name'=>'required',
			'description'=>'required'
		];
		
		$messages = [
		    'name.required' => 'El nombre es requerido',
			'description.required' => 'La descripción es requerida'
		];
		
		$this->validate($request,$reglas, $messages);
		$category_data = $request->all();
		$category = Category::create($category_data);
		/*return response()->json(['data'=>$category],200);*/
		return $this->showOne($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        /*$category = Category::findOrFail($id);*/
		/*return response()->json(['data'=>$category],200);*/
		return $this->showOne($category);
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
    public function update(Request $request, Category $category)
    {
        if($request->has('name')){
			$category->name = $request->name;
		}
		
		if ($request->has('description')){
			$category->description = $request->name;
		}
		
		$category->save();
		
		return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        /*$category->delete();*/
		return $this->showOne($category);
    }
}
