<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $table="product_details";

    protected $guarded=[];

    public $timestamps=false;

    public function product(){
        return $this->belongsTo('App\Models\Product',"product_id");
    }
}
