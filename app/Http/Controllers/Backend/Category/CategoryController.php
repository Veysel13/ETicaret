<?php

namespace App\Http\Controllers\Backend\Category;

use App\Http\Controllers\Controller;
use App\Jobs\ESRestaurantIndex;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Restaurant\RestaurantInterface;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    private $category;
    private $restaurantRepository;

    public function __construct(CategoryInterface $category, RestaurantInterface $restaurant)
    {
        $this->category = $category;
        $this->restaurantRepository = $restaurant;
    }

    public function xhrIndex(Request $request, int $restaurantId): object
    {
        $blade = [];

        $limit = $request->has('length') ? intval($request->input('length')) : 20;

        $categories = $this->category->findByRestaurantIdCategoriesPaginate($restaurantId, $limit);

        $categories->map(function ($item) {

            $item->imageUrl = $item->image_url ?? '';

            $item->editUrl = route('backend.category.edit', $item->id);
            $item->removeUrl = route('backend.category.remove', $item->id);

            $item->totalFoods = $this->category->foodCount($item->id);

            return $item;
        });

        $blade['draw'] = $request->input('draw');
        $blade['recordsTotal'] = $categories->total();
        $blade['recordsFiltered'] = $categories->total();
        $blade['data'] = $categories->toArray()['data'];
        return response()->json($blade);
    }

    public function products($id)
    {
        $category = $this->category->findById($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'errors' => ['Category Not Found']
            ]);
        }

        $blade = [];
        $products = $this->category->products($category->id);
        $products->map(function ($food) {
            $food->editUrl = route('backend.product.edit', $food->product_id);
            $food->removeUrl = route('backend.product.remove', $food->product_id);

            $food->barcode=$food->barcode??'';
            $food->tax=$food->tax??'';
            $food->brand=$food->brand??'';

            return $food;
        });

        $blade["products"] = $products;

        return response()->json([
            'status' => true,
            'data' => $blade
        ]);
    }

    public function edit($id)
    {
        $category = $this->category->findById($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'errors' => [['Category Not Found']]
            ], 500);
        }

        $restaurant = $this->restaurantRepository->findById($category->restaurant_id);
        if (!$restaurant) {

            return response()->json([
                'status' => false,
                'errors' => [['Restaurant Not Found']]
            ], 500);
        }

        $categoryFoods = $this->category->products($category->id);

        $blade = [];
        $blade['restaurant'] = $restaurant;
        $blade['category'] = $category;
        $blade['categoryFoods'] = $categoryFoods;

        $content = \View::make('backend.restaurant.categoryEdit', $blade);
        return response()->json([
            'status' => true,
            'content' => $content->render()
        ]);
    }

    public function store(Request $request, $restaurantId)
    {

        $request->validate([
            'name' => 'string|required'
        ]);

        $restaurant = $this->restaurantRepository->findById($restaurantId);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'errors' => [['Restaurant Not Found']]
            ], 500);
        }

        $data = [
            'restaurant_id' => $restaurantId,
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'status' => $request->input('status', 0),
            'sort' => $request->input('sort', 0)
        ];

        // Image Upload
        $file = $request->file('image');
        if ($file) {

            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $newName = date('Ymd') . '_' . \Str::slug(substr($name, 0, 20)) . '_' . \Str::random(6) . '.' .
                $file->getClientOriginalExtension();

            $path = \Storage::disk('uploads')->putFileAs('categories', new File($file), $newName);
            $data['image'] = $path;
        }

        $category = $this->category->create($data);

        $this->category->findByIdProductRemove($category->id);
        if ($request->input('products') && is_array($request->input('products'))) {
            $foodIds = array_filter($request->input('products'), 'strlen');
            foreach ($foodIds as $i => $foodId) {
                $this->category->productCreate([
                    'category_id' => $category->id,
                    'product_id' => $foodId,
                    'sort' => $i
                ]);
            }
        }

        dispatch(new ESRestaurantIndex($category->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Category Store'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|required'
        ]);

        $category = $this->category->findById($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'errors' => [['Category Not Found']]
            ],500);
        }

        $restaurant = $this->restaurantRepository->findById($category->restaurant_id);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'errors' => [['Restaurant Not Found']]
            ], 500);
        }

        $data = [
            'name' => $request->input('name'),
            'status' => $request->input('status', 0),
            'sort' => $request->input('sort', 0)
        ];

        if ($request->has('image_remove')) {
            $data['image'] = null;
        }

        $file = $request->file('image');
        if ($file) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $newName = date('Ymd') . '_' . \Str::slug(substr($name, 0, 20)) . '_' . \Str::random(6) . '.' .
                $file->getClientOriginalExtension();

            $path = \Storage::disk('uploads')->putFileAs('categories', new File($file), $newName);
            $data['image'] = $path;
        }

        $category = $this->category->update($id, $data);

        $this->category->findByIdProductRemove($category->id);
        if ($request->input('products') && is_array($request->input('products'))) {
            $foodIds = array_filter($request->input('products'), 'strlen');
            foreach ($foodIds as $i => $foodId) {
                $this->category->productCreate([
                    'category_id' => $category->id,
                    'product_id' => $foodId,
                    'sort' => $i
                ]);

            }
        }

        dispatch(new ESRestaurantIndex($category->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Category Edit'
        ]);
    }

    public function remove(int $id)
    {
        $category = $this->category->findById($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'errors' => [['Category Not Found']]
            ],500);
        }

        $this->category->findByIdProductRemove($id);
        $this->category->remove($id);

        dispatch(new ESRestaurantIndex($category->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Category Delete'
        ]);
    }
}
