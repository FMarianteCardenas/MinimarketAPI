<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component \HttpKernel \Exception \ MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
	use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
		if($exception instanceof ValidationException ){
			return $this->convertValidationExceptionToResponse($exception, $request);
		}
		
		if($exception instanceof ModelNotFoundException){
			$modelo = strtolower(class_basename($exception->getModel()));
			return $this->errorResponse("No existe ningun {$modelo} con la id especificada",404);
		}
		if($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }
		if($exception instanceof AuthorizationException){
			return $this->errorResponse('Usted No Tiene Permisos Para Realizar La Acción',403);
		}
		if($exception instanceof NotFoundHttpException){
			return $this->errorResponse('No se Encontró la URL Especificada',404);
		}
		if($exception instanceof MethodNotAllowedHttpException){
			return $this->errorResponse('El método especificado en la petición no es válido',405);
		}
		
		if($exception instanceof HttpException){
			return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
		}
		
		if($exception instanceof QueryException){
			$codigo = $exception->errorInfo[1];
			if($codigo == 1451){
				return $this->errorResponse('No se puede eliminar un recurso que se encuentra como llave foranea en otro.', 409);
			}
			else{
				return response()->json(['error'=>$exception->errorInfo[2],'code'=>$exception->errorInfo[1]],409);
			}
			
		}

        if($exception instanceof TokenMismatchException){
            return redirect('/login')->withInput();
        }
		
		if(config('app.debug')){
			return parent::render($request, $exception);
		}
		
		return $this->errorResponse('Falla Inesperada. Intente más tarde.',500);
        
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($this->isWeb($request)){
            return redirect()->guest(route('login'));
        }
        else{
            return $this->errorResponse('Usted No se Encuentra Autenticado', 401);
        }
        

        
    }
	
	/**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        if($this->isWeb($request)){
            return $request->ajax()? response()->json($errors,422): redirect()->back()->withInput($request->all())->withErrors($errors);
        }
        return $this->errorResponse($errors,422);
    }

    /**
    * determina si es una petición web
    */

    private function isWeb($request){
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
