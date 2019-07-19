<?php

namespace App;

use App\Sale;
use App\User;
use App\Product;
use App\Category;
use App\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Minimarket extends Model
{
	use SoftDeletes;
	
	const IS_ACTIVE = '1';
	const IS_NOT_ACTIVE = '0';

    protected $fillable = [
        'name',
        'address',
        'patent',
        'is_active'
    ];
	
	protected $dates = ['deleted_at'];

    public function disponible(){
    	return $this->is_active == Minimarket::IS_ACTIVE;
    }

    public function users(){
    	return $this->hasMany(User::class);
    }

    public function products(){
    	return $this->belongsToMany(Product::class,'minimarket_product','minimarket_id','product_id')
		->withPivot('stock', 'buy_price','sale_price');
    }
	
	/*public function products(){
		return $this->hasMany(Product::class);
	}*/

    public function categories(){
    	return $this->belongsToMany(Category::class,'category_minimarket','minimarket_id','category_id');
    }

    public function sales(){
    	return $this->hasMany(Sale::class);
    }

    public function subscriptions(){
        return $this->belongsToMany(Subscription::class,'minimarket_subscription','minimarket_id','subscription_id')->withPivot('start_date', 'expiration_date','is_active');
    }

}
