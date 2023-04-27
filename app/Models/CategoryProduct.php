<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CategoryProduct extends Model
{
    protected $table = 'category_products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'product_id',
        'sort'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function food()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Filter Function
     *
     * @param $query
     * @param  Request  $request
     * @return Builder
     */
    public function scopeFilter($query, Request $request): Builder
    {
        if ($request->filled('foodSearch')) {
            $query->where('products.name', 'like', '%' . $request->input('foodSearch') . '%');
        }

        return  $query;
    }
}
