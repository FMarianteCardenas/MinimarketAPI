<?php

namespace App\Providers;

use App\User;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        /*se ejecuta cuando se crea un usuario*/
        User::created(function($user){
            /*retry intenta 5 veces enviar un email cada 100 milisegundos*/
            retry(5,function() use($user){
               Mail::to($user->email)->send(new UserCreated($user));
            },100);
           
        });

        /*se ejecuta cuando se haya actualizado un usuario(para este caso solo si se modificó el correo)*/
        User::updated(function($user){
           if($user->isDirty('email')){

            retry(5, function() use($user){
                Mail::to($user->email)->send(new UserMailChanged($user));
            },100);
             
           }
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
