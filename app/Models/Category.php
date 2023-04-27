<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Category extends Model
{
    use SoftDeletes;

    protected $table="categories";
    protected $fillable=['restaurant_id',"status","name","slug","image","sort"];
    protected $guarded=[];

//    const CREATED_AT = "created_at";
//    const UPDATED_AT = "updated_at";
//    const DELETED_AT = "deleted_at";

    public function getImageUrlAttribute()
    {
        if ($this->image !== '' && $this->image) {
            return \Storage::disk('uploads')->url($this->image);
        }
        return '';
    }

    public function products(){
        return $this->belongsToMany(Product::class,"category_products");
    }

    public function scopeFilter($query, Request $request): Builder
    {
        if ($request->filled('name')) {
            $query->where('categories.name', 'LIKE', '%' . $request->input('name') . '%');
        }

        if ($request->filled('foodSearch')) {
            $query->join("category_products", "category_products.category_id", "=", "categories.id")
                ->join("products", "products.id", "=", "category_products.product_id")
                ->where('products.name', 'LIKE', '%' . $request->input('foodSearch') . '%')
                ->groupBy('categories.id');
        }

        return  $query;
    }

}
