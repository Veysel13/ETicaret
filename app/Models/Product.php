<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table="products";

    protected $guarded=[];

    public function getImageUrlAttribute()
    {
        if ($this->image !== '' && $this->image) {
            return \Storage::disk('uploads')->url($this->image);
        }

        return 'http://via.placeholder.com/400x400?text=UrunResmi';
    }

    public function categories(){
        return $this->belongsToMany(Category::class,"category_products");
    }

    public function detail(){
        return $this->hasOne(ProductDetail::class);
    }
}
