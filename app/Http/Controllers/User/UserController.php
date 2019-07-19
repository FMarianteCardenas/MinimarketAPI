<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Minimarket;
use Illuminate\Http\Request;
use App\Http\Controllers\Apicontroller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Mail;

class UserController extends Apicontroller
{
	 public function __construct(){
        //$this->middleware('client.credentials')->only(['index','show']);
        $this->middleware('auth:api')->except(['verify','resend','store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	/*sort data creado por pancho*/
		//$model = new User;
		//$collection = $this->setBasic($model);
        //return new UserCollection($collection);

		//return $this->showAll($collection);
		
        /*$users = User::all();
        /*return response()->json(['data' => $users],200);*/
		
		/*usando el trait que retorna una coleccion*/
		/*return $this->showAll($users);*/

		/*sort data creado por mi*/
		/*$collection = User::all();
		$collection = $this->sortData2($collection);
		return new UserCollection($collection);*/

		/*set Model*/
		$model = new User;
		$collection = $this->setModel($model);
		return new UserCollection($collection);
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
		   'name' => 'required',
		   'lastname' => 'required',
		   'email' => 'required|email|unique:users',
		   'username' => 'required|unique:users',
		   'password' => 'required|min:6|confirmed',
		   'minimarket_id' => 'required'
		];
		
		$messages = [
		'name.required' => 'El Nombre es Requerido',
		'lastname.required' => 'El Apellido es Requerido',
		'email.required' => 'El Email es Requerido',
		'email.email' => 'Ingrese un Email Valido',
	    'email.unique' => 'El Email ya se encuentra Registrado',
		'username.required' => 'El Nombre de Usuario es Requerido',
		'username.unique' => 'El Nombre de Usuario ya está en uso',
		'password.required' => 'El Password es Requerido',
		'password.min' => 'El Password debe tener al menos 6 caracteres',
		'password.confirmed' => 'Debe proporcionar la confirmacion del password',
		'minimarket_id.required' => 'Debe indicar el id del minimercado'
		];
		
		$this->validate($request,$reglas,$messages);
		
        $user_data = $request->all();
		$user_data['password'] = bcrypt($request->password);
		$user_data['is_active'] = User::IS_NOT_ACTIVE;
		$user_data['is_admin'] = User::IS_NOT_ADMIN;
		$user_data['is_owner'] = User::IS_NOT_OWNER;
		$user_data['verification_token'] = User::generateVerificationToken();
		$user = User::create($user_data);
		/*return response()->json(['data' => $user],201);*/
		//return $this->showOne($user,201);
		UserResource::withoutWrapping();
		return  new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        /*$user = User::findOrFail($id);*/
			/*$minimarket = Minimarket::find($user->minimarket_id);
			return response()->json(['data' => $user,'relationships'=>['minimarket'=>$minimarket]],200);*/
	    /*return $this->showOne($user);*/
		UserResource::withoutWrapping();
		return  new UserResource($user);
		
		/*else{
			/*return response()->json(['data' => 'user not found'],404);*/
			/*return $this->errorResponse('usuario no encontrado',404);
		}*/
		
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
    public function update(Request $request, User $user)
    {
		/*$user = User::findOrFail($id);*/
		
        $reglas = [
		   'email' => 'email|unique:users,email,'.$user->id,
		   'username' => 'unique:users,username,'.$user->id,
		   'password' => 'min:6|confirmed'
		];
		
		if($request->has('name')){
			$user->name = $request->name;
		}
		if($request->has('lastname')){
			$user->lastname = $request->lastname;
		}
		if($request->has('email') && $user->email != $request->email){
			$user->is_active = User::IS_NOT_ACTIVE;
			$user->verification_token = User::generateVerificationToken();
			$user->email = $request->email;
		}
		if($request->has('username') && $user->username != $request->username){
			$user->username = $request->username;
		}
		/*verifica si el usuario ha sido modificado en algún campo*/
		if(!$user->isDirty()){
			return $this->errorResponse('debe especificar al menos un campo diferente para actualizar',422);
			//return response()->json(['error'=>'debe especificar al menos un campo diferente para actualizar','code'=>422],422);
		}
		
		$user->save();
		/*return response()->json(['data'=>$user],200);*/
		/*return $this->showOne($user,200);*/
		UserResource::withoutWrapping();
		return  new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
		/*cambiando al estado desactivado del usuario*/
        /*$user = User::findOrFail($id);*/
		$user->is_active = 0;
		$user->save();
		/*return response()->json(['data'=>$user],200);*/
		//return $this->showOne($user,200);
		UserResource::withoutWrapping();
		return  new UserResource($user);
    }

    /*verificar el correo del usuario cuando se envia el token por email*/
    public function verify($token){
       $user = User::Where('verification_token','=',$token)->firstOrFail();
       $user->is_active = 1;
       $user->verification_token = null;
       $user->save();
       UserResource::withoutWrapping();
	   return  new UserResource($user);
    }

    /*reenviar el correo de verificacion con el verification_token*/
    public function resend(User $user){
       
       if($user->isActive()){
       	 return $this->errorResponse('El usuario ya esta verificado',409);
       }

       retry(5,function() use($user){
               Mail::to($user->email)->send(new UserCreated($user));
       		   return $this->showMessage('Email de Confirmación enviado',200);
       },100);
       
    }
}
