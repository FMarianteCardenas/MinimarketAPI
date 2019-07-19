<?php

namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser{
	
	private function successResponse($data, $code){
		return response()->json($data, $code);
	}
	
	protected function errorResponse($message, $code){
		return response()->json(['error' => $message, 'code' => $code], $code);
	}
	
	protected function showAll(Collection $collection, $code = 200){
		return $this->successResponse(['data' => $collection], $code);
	}
	
	protected function showOne(Model $instance, $code = 200){
		return $this->successResponse(['data' => $instance], $code);
	}

    protected function showMessage($message,$code = 200){
        return response()->json(['message'=>$message,'code'=>$code],$code);
    }
    
    	
	/*respuesta para datos que no vienen de un modelo (tablas pivot)*/
	protected function showCustomResponse($data,$code){
		return $this->successResponse(['data' => $data],$code);
	}
	
	protected function sortData(Collection $collection) {
        if (request()->has('sort')) {

            $attribute = request()->sort;
            $collection = $collection->sortBy($attribute);
        }
        return $collection;
    }

    protected function setBasic($resource, $relations = null, $filter = true, $sort = true, $paginate = null) {

        if ($filter){
            $resource = $this->filter($resource);
        }
        if ($sort) {
            $resource = $this->sort($resource);
        }
        $limit = 10;
        if (request()->has('limit')) {
            $limit = request()->limit;
        }
        if (request()->has('soft')) {
            $resource = $resource->withTrashed();
        }

        if (!empty($relations)) {
            $resource = $resource->with($relations);
        }
        if ($paginate == 'simple') {
            $collection = $resource->simplePaginate($limit);
        } else {
            $collection = $resource->paginate($limit);
        }
        return $collection;
    }

    private function filter($resource) {
        foreach (request()->query() as $attribute => $value) {
            if (isset($attribute) && $attribute != 'soft' && $attribute != 'sort' && $attribute != 'limit' && $attribute != 'page' && $attribute != 'simple' && $attribute != 'simple2' && $attribute != 'alone' && $attribute != 'obs'
            ) {
                $value = urldecode($value);
                $encode = urlencode($value);
                if (strstr($value, '><')) {
                    $value = str_replace('><', '', $value);
                    $resource = $resource->where($attribute, 'like', '%' . $value . '%');
                } else
                if (strstr($value, 'notnull')) {
                    $resource = $resource->whereNotNull($attribute);
                } else
                if (strstr($value, 'null')) {

                    $resource = $resource->whereNull($attribute);
                } else
                if (strstr($value, '!')) {
                    $value = str_replace('!', '', $value);
                    $resource = $resource->where($attribute, '<>', $value);
                } else
                if (strstr($value, '>')) {

                    $value = str_replace('>', '', $value);
                    $resource = $resource->where($attribute, '>', $value);
                } else
                if (strstr($value, '>=')) {

                    $value = str_replace('>=', '', $value);
                    $resource = $resource->where($attribute, '>=', $value);
                } else
                if (strstr($value, '<')) {

                    $value = str_replace('<', '', $value);
                    $resource = $resource->where($attribute, '<', $value);
                } else
                if (strstr($value, '<=')) {

                    $value = str_replace('<=', '', $value);
                    $resource = $resource->where($attribute, '<=', $value);
                } else
                    $resource = $resource->where($attribute, $value);
            }
        }
        return $resource;
    }

    private function sort($resource) {
        if (request()->has('sort')) {
            $attributes = explode(',', request()->sort);

            foreach ($attributes as $attribute) {
                $order = 'asc';
                $cast = false;
                if (!empty($attribute)) {
                    /*strstr($atributo,'texto')busca si en el atributo viene el texto que se pasa (retorna true o false)*/
                    if (strstr($attribute, 'group')) {
                        $attribute = str_replace('group', 'products.group', $attribute);
                    }
                    if (strstr($attribute, 'location')) {
                        $attribute = str_replace('location', 'locations.name', $attribute);
                    }
                    if (strstr($attribute, '--')) {
                        $cast = true;
                        $order = 'desc';
                        $attribute = str_replace('--', '', $attribute);
                    }
                    if (strstr($attribute, '-')) {
                        $order = 'desc';
                        $attribute = str_replace('-', '', $attribute);
                    }
                    if (strstr($attribute, '++')) {
                        $cast = true;
                        $order = 'asc';
                        $attribute = str_replace('++', '', $attribute);
                    }
                    if (strstr($attribute, '+')) {
                        $order = 'asc';
                        $attribute = str_replace('+', '', $attribute);
                    }

                    if ($cast)
                        $resource = $resource->orderByRaw("CAST($attribute AS SIGNED) DESC");
                    else
                        $resource = $resource->orderBy(trim($attribute), $order);
                }
            }
        }
        return $resource;
    }

    public function validationErrorsToString($errArray) {
        $valArr = array();
        foreach ($errArray->toArray() as $key => $value) {
            array_push($valArr, $value[0]);
        }
        if (!empty($valArr)) {
            $errStrFinal = implode(',', $valArr);
        }
        return $errStrFinal;
    }

    public function allowActions($route, $actions, $roles) {

        foreach ($actions as $action) {
            if($route->getActionMethod() == $action) {
                $auth = auth('api')->user();

                if(!empty($auth)) {
                    $auth->authorizeRoles($roles);
                }
            }
        }
    }
    

    /*setea un modelo y devuelve una colección*/
    protected function setModel($resource,$filter = true,$sort_by = true,$paginate = null){
        /*si el filtro es true*/
        if($filter){
            /*llama a la funcion filtrar*/
            $resource = $this->filtrar($resource);
        }

        if($sort_by){
            $resource = $this->sortDataBy($resource);
        }

        /*por defecto se pagina por 10 elementos*/
        $limit = 10;
        if (request()->has('limit')) {
                    $limit = request()->limit;
        }
        if ($paginate == 'simple') {
            /*appends concatena atributos extras de la url*/
                    $collection = $resource->simplePaginate($limit)->appends(request()->all());
        } else {
                    $collection = $resource->paginate($limit)->appends(request()->all());
        }

        /*paginando el recurso*/
        //$collection = $this->paginate($collection);

        return $collection;
    }
    
    private function filtrar($resource){
        /*$attribute me entrega el nombre del campo y $value el valor buscado*/
        foreach(request()->query as $attribute => $value){
            //dd($attribute);
            if(isset($attribute) && $attribute != 'sort_by' && $attribute != 'page' && $attribute != 'limit'){
                /*decodifica el valor del campo*/
                $value = urldecode($value);
                /*codifica el valor del campo*/
                $encode = urlencode($value);
                
                if(strstr($value,'><')){
                    /*si el valor contiene los caracteres >< debo usar un like*/
                    $value = str_replace('><','',$value);
                    $resource = $resource->where($attribute,'like','%'.$value.'%');
                    //dd($resource);
                }
                else if(strstr($value, '!')){
                    /*si el valor contiene ! usa un entre (<>)*/
                    $value = str_replace('!', '', $value);
                    $resource = $resource->where($attribute,'<>',$value);
                }
                else{
                    /*si no tiene ningun caracter especial solo se usa donde coincida el valor*/
                    $resource = $resource->where($attribute,'=',$value);
                    //dd("entro");
                }
            }
            
        }
        return $resource;
    }

    /*ordenar los registros de acuerdo a un atributo ejemplo ?sort_by=atributo(columna de la tabla)*/
    protected function sortDataBy($resource){
        if(request()->has('sort_by')){
            /*separando los atributos que vienen separados por coma*/
            $attributes = explode(',',request()->sort_by);
            
            foreach($attributes as $attribute){
                /*por cada atributo*/
                $order = 'asc';
                $cast = false;
                if (strstr($attribute, '--')) 
                {
                        $cast = true;
                        $order = 'desc';
                        $attribute = str_replace('--', '', $attribute);
                }
                if (strstr($attribute, '-')) {
                        $order = 'desc';
                        $attribute = str_replace('-', '', $attribute);
                }
                if (strstr($attribute, '++')) {
                        $cast = true;
                        $order = 'asc';
                        $attribute = str_replace('++', '', $attribute);
                }
                if (strstr($attribute, '+')) {
                        $order = 'asc';
                        $attribute = str_replace('+', '', $attribute);
                }

                if ($cast){
                    $resource = $resource->orderByRaw("CAST($attribute AS SIGNED) DESC");
                }
                else{
                    $resource = $resource->orderBy(trim($attribute), $order);
                }
                        
            }
            //$attribute = request()->sort_by;
            //$collection = $collection->sortBy($attribute);
        }
        //return $collection;
        return $resource;
    }

    protected function paginate(Collection $collection){
        /*resuelve la pagina actual*/
        $page = LengthAwarePaginator::resolveCurrentPage();

        /*elementos por pagina*/
        $perPage = 2;

        /*hace el calculo de división de la collection*/
        $results = $collection->slice(($page-1)*$perPage,$perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(),$perPage,$page,[
            'path' => LengthAwarePaginator::resolveCurrentPage()
        ]);
        
        /*agregando los demás parametros de la url si los tuviera*/
        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function setCollection($collection, $filter = true, $paginate = 'normal'){
        $limit = 10;
        if($filter){
            $collection = $this->filtrarCollection($collection);
        }

        if ($paginate == 'simple') {
            $collection = $collection->simplePaginate($limit)->appends(request()->all());
        } else {
            $collection = $collection->paginate($limit)->appends(request()->all());
        }
        return $collection;
    }

    public function filtrarCollection($resource){
        //dd($collection);
        $collection = $resource;
        $limit = 2;
        foreach (request()->query() as $attribute => $value){
            if(isset($attribute) && $attribute != 'page'){
                $value = urldecode($value);
                //dd($attribute,$value);
                /*si el valos contiene un >< uso un like*/
                if(strstr($value,'><')){
                    $value = str_replace('><', '', $value);
                    $collection = $collection->where($attribute,'like','%'.$value.'%');
                }
                else{
                    $collection = $collection->where($attribute,$value);
                }
            }
            
            
        }
        
        //dd($collection);
        //$collection = $collection->paginate($limit)->appends(request()->all());

        //dd($collection);
        return $collection;
        //dd($filter);
    }

}