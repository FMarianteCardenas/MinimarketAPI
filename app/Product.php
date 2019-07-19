<?php

namespace App;

use App\Sale;
use App\Product;
use App\Minimarket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	
	const IS_ACTIVE = '1';
	const IS_NOT_ACTIVE = '0';
     
    protected $fillable = [
    	'code',
    	'name',
    	'description',
		/*'stock',
		'buy_price',
		'sale_price',
    	'is_active',*/
    	'category_id'
		/*'minimarket_id'*/
    ];
	
	protected $dates = ['deleted_at'];

    public function disponible(){
    	return $this->is_active == Product::IS_ACTIVE;
    }

    public function minimarkets(){
    	return $this->belongsToMany(Minimarket::class,'minimarket_product','product_id','minimarket_id')
		->withPivot('stock', 'buy_price','sale_price');
    }
	
	/*public function minimarket(){
		return $this->belongsTo(Minimarket::class);
	}*/

    public function category(){
    	return $this->belongsTo(Category::class);
    }

    public function sales(){
    	return $this->belongsToMany(Sale::class,'sale_product','product_id','sale_id');
    }
}
