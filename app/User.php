<?php

namespace App;

use App\Sale;
use App\Minimarket;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    const IS_ADMIN = '1';
    const IS_NOT_ADMIN = '0';

    const IS_ACTIVE = '1';
    const IS_NOT_ACTIVE = '0';

    const IS_OWNER = '1';
    const IS_NOT_OWNER = '0';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'username',
        'email',
        'password',
        'is_admin',
        'is_active',
        'is_owner',
        'verification_token',
        'minimarket_id'
    ];
	
	protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token'
    ];
	
	/*mutador (transformar datos antes de insertar en la bd) para dejar en minuscula el nombre*/
	public function setNameAttribute($valor){
		$this->attributes['name'] = strtolower($valor);
	}
	
	/*mutador (transformar datos antes de insertar en la bd) para dejar en minuscula el email*/
	public function setEmailAttribute($valor){
		$this->attributes['email'] = strtolower($valor);
	}
	
	/*accesor (transformar datos para "mostrar" sin necesidad de modificarlo en la bd) para dejar en minuscula el nombre*/
	public function getNameAttribute($valor){
		/*muestra el nombre con la primera letra en mayúscula ("de cada palabra")*/
		return ucwords($valor);
		/*return ucfirst($valor); muestra solo pa primera palabra en Mayúscula("primera letra")*/
	}

    public function isActive(){
      return $this->is_active == User::IS_ACTIVE;
    }

    public function isAdmin(){
      return $this->is_admin == User::IS_ADMIN;
    }

    public static function generateVerificationToken(){
        return str_random(40);
    }

    public function minimarket(){
        return $this->belongsTo(Minimarket::class);
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
