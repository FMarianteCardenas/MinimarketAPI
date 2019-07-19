<?php

namespace App;

use App\Product;
use App\Minimarket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
        'name',
        'description'
    ];
	
	protected $dates = ['deleted_at'];

    public function products(){
    	return $this->hasMany(Product::class);
    }

    public function minimarkets(){
    	return $this->belongsToMany(Minimarket::class,'category_minimarket','category_id','minimarket_id');
    }
}
