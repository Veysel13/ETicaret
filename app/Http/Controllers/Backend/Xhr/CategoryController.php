<?php

namespace App\Http\Controllers\Backend\Xhr;

use App\Http\Controllers\Controller;
use App\Jobs\ESRestaurantIndex;
use App\Models\Category;
use App\Models\Restaurant;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;
    public function __construct(CategoryInterface $category)
    {
        $this->category=$category;
    }
    public function categories(Request $request){

        $result = [];

        $categories =  Category::filter(request())
            ->where('status',1)
            ->orderBy('created_at')
            ->take(100)->get();

        $items = [];
        foreach ($categories as $category) {
            array_push($items, [
                'id' => $category->id,
                'text' => $category->name
            ]);
        }

        $result['restaurants'] = $items;
        return response()->json($result);
    }

    public function statusUpdate(Request $request)
    {
        $refId = $request->input('refId');
        $newId = $request->input('newId');

        $category = $this->category->findById($refId);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found'
            ]);
        }

        $this->category->update($refId,['status' => $newId]);

        dispatch(new ESRestaurantIndex($category->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Category Update'
        ]);
    }
}
