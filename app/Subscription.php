<?php

namespace App;

use App\Minimarket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Subscription extends Model
{
    use SoftDeletes;
	
	const IS_ACTIVE = '1';
	const IS_NOT_ACTIVE = '0';

    protected $fillable = [
    	'name',
    	'duration',
    	'price',
        'is_active'
    ];
	
	protected $dates = ['deleted_at'];

	public function minimarkets(){
    	return $this->belongsToMany(Minimarket::class,'minimarket_subscription','subscription_id','minimarket_id')->withPivot('start_date', 'expiration_date','is_active');
    }
}
