<?php

namespace App;

use App\User;
use App\Minimarket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'total_sale',
    	'minimarket_id',
    	'user_seller_id',
    ];
	
	protected $dates = ['deleted_at'];


    public function minimarket(){
    	return $this->belongsTo(Minimarket::class);
    }

    public function seller(){
    	return $this->belongsTo(User::class,'user_seller_id');
    }
	
    public function products(){
        return $this->belongsToMany(Product::class,'sale_product','sale_id','product_id')->withPivot('quantity');
    }
}
