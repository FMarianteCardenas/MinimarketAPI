<?php

namespace App\Http\Controllers\Api;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Http\Resources\User\UserResource;

use Lcobucci\JWT\Parser;
use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Login de usuario y generar token de acceso
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
    	//dd($request);
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
            //'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => $user,
            'minimarket'=>$user->minimarket
        ]);
    }

    /**
     * Cerrar sesión de un usuario en la api (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
    	//dd($user);
    	//$user = Auth::user()->token();
        //$user->revoke();
        //dd($request->bearerToken());
        //$request->user()->token()->revoke();
        // return response()->json([
        //     'message' => 'Sesion cerrada correctamente'
        // ]);
        $value = $request->bearerToken();
        if ($value) {
 
        $id = (new Parser())->parse($value)->getHeader('jti');
        $revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
        //$this->guard()->logout();
    }
    Auth::logout();
    return Response(['code' => 200, 'message' => 'Sesion Cerrada correctamente'], 200);
    }
}
