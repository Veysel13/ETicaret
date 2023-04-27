<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use App\Jobs\ESRestaurantIndex;
use App\Models\CategoryProduct;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Restaurant\RestaurantInterface;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private $product;
    private $category;
    private $restaurant;

    public function __construct(
        ProductInterface    $product,
        CategoryInterface   $category,
        RestaurantInterface $restaurant
    )
    {
        $this->product = $product;
        $this->category = $category;
        $this->restaurant = $restaurant;
    }


    public function edit($id)
    {
        $product = $this->product->findById($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        $restaurant = $this->restaurant->findById($product->restaurant_id);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant Not Found'
            ]);
        }

        $blade = [];
        $blade['product'] = $product;
        $blade['restaurant'] = $restaurant;

        $content = \View::make('backend.restaurant.productEdit', $blade);
        return response()->json([
            'status' => true,
            'content' => $content->render()
        ]);
    }

    public function store(Request $request, $restaurantId)
    {
        $request->validate([
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
        ]);

        $restaurant = $this->restaurant->findById($restaurantId);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'errors' => [['Restaurant Not Found']]
            ], 500);
        }

        $data = [
            'status' => $request->input('status', 0),
            'restaurant_id' => $restaurant->id,
            'name' => Str::title($request->input('name')),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'price' => $request->input('price', 0),
            'sort' => $request->input('sort', 0),
        ];

        $file = $request->file('image');
        if ($file) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $newName = date('Ymd') . '_' . \Str::slug(substr($name, 0, 20)) . '_' . \Str::random(6) . '.' .
                $file->getClientOriginalExtension();

            $path = \Storage::disk('uploads')->putFileAs('products', new File($file), $newName);
            $data['image'] = $path;
        }

        $product = $this->product->create($data);

        if ($request->input('category_id')) {
            $product->categories()->attach($request->input('category_id'));
        }

        dispatch(new ESRestaurantIndex($product->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Prodcut Store'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
        ]);

        $product = $this->product->findById($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        $restaurant = $this->restaurant->findById($product->restaurant_id);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'errors' => [['Restaurant Not Found']]
            ], 500);
        }

        $data = [
            'status' => $request->input('status', 0),
            'restaurant_id' => $restaurant->id,
            'name' => Str::title($request->input('name')),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'price' => $request->input('price', 0),
            'sort' => $request->input('sort'),
        ];

        if ($request->has('image_remove')) {
            $data['image'] = null;
        }

        $file = $request->file('image');
        if ($file) {

            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $newName = date('Ymd') . '_' . \Str::slug(substr($name, 0, 20)) . '_' . \Str::random(6) . '.' .
                $file->getClientOriginalExtension();

            $path = \Storage::disk('uploads')->putFileAs('products', new File($file), $newName);
            $data['image'] = $path;
        }

        $this->product->update($id, $data);

        dispatch(new ESRestaurantIndex($product->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Product Update',
        ]);
    }

    public function remove(int $id)
    {
        $product = $this->product->findById($id);

        $this->product->findByIdProductRemove($id);
        $this->product->remove($id);

        dispatch(new ESRestaurantIndex($product->restaurant_id));

        return response()->json([
            'status' => true,
            'message' => 'Product Remove'
        ]);
    }

    public function reOrder(Request $request)
    {
        $request->validate([
            'ids' => 'array'
        ]);

        foreach ($request->input('ids') as $i => $id) {

            CategoryProduct::where('category_id', $request->input('categoryId'))
                ->where('product_id', $id)
                ->update(['sort' => $i + 1]);
        }

        return response()->json([
            'status' => true,
            'message' => 'reOrdered'
        ]);
    }
}
