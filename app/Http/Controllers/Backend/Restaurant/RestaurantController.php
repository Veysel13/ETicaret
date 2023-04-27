<?php


namespace App\Http\Controllers\Backend\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Jobs\ESRestaurantIndex;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Repositories\Restaurant\RestaurantInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{

    private $restaurant;
    private $user;

    public function __construct(RestaurantInterface $restaurant, UserInterface $user)
    {
        $this->restaurant = $restaurant;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $blade = [];
        $blade['pageTitle'] = 'Restaurant List';
        return view('backend.restaurant.index', $blade);
    }

    public function xhrIndex(Request $request)
    {

        $blade = [];

        $limit = $request->has('length') ? intval($request->input('length')) : 20;

        $restaurants = $this->restaurant->relationPaginate($limit);

        $restaurants->map(function ($item) {

            $item->imageUrl = $item->logo_url ?? '';

            $item->editUrl = route('backend.restaurant.edit', $item->id);

            $item->removeUrl = route('backend.restaurant.delete', $item->id);

            return $item;
        });

        $blade['draw'] = $request->input('draw');
        $blade['recordsTotal'] = $restaurants->total();
        $blade['recordsFiltered'] = $restaurants->total();
        $blade['data'] = $restaurants->toArray()['data'];
        return response()->json($blade);
    }

    public function create(Request $request)
    {

        return view('backend.store.create');
    }

    public function store(RestaurantRequest $request)
    {

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'status' => $request->input('status', 0)
        ];

        $file = $request->file('logo');
        if ($file) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time();

            $newName = $name . '.' . $file->getClientOriginalExtension();

            $path = \Storage::disk('uploads')->putFileAs('restaurants', new File($file), $newName);
            $data['logo'] = $path;
        }

        $restaurant=$this->restaurant->create($data);

        dispatch(new ESRestaurantIndex($restaurant->id));

        return response()->json([
            'status' => true,
            'message' => 'New restaurant added successfully',
        ]);
    }

    public function edit($id)
    {

        $restaurant = $this->restaurant->findById($id);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant not found'
            ]);
        }

        $blade = [];
        $blade['restaurant'] = $restaurant;

        $content = \View::make('backend.restaurant.edit', $blade);
        return response()->json([
            'status' => true,
            'content' => $content->render()
        ]);
    }

    public function update($id, RestaurantRequest $request)
    {

        $restaurant = $this->restaurant->findById($id);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'errors' => ['Restaurant not found']
            ], 500);
        }

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'status' => $request->input('status', 0)
        ];

        if ($request->has('logo_remove')) {
            $data['logo'] = null;
        }

        $file = $request->file('logo');
        if ($file) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time();

            $newName = $name . '.' . $file->getClientOriginalExtension();

            $path = \Storage::disk('uploads')->putFileAs('restaurants', new File($file), $newName);
            $data['logo'] = $path;
        }

        $this->restaurant->update($restaurant->id, $data);

        dispatch(new ESRestaurantIndex($restaurant->id));

        return response()->json([
            'status' => true,
            'message' => 'Restaurant Edit',
        ]);
    }

    public function delete($id)
    {
        $this->restaurant->remove($id);
        CategoryProduct::whereIn('category_id',Category::where('restaurant_id',$id)->pluck('id'))->delete();
        Category::where('restaurant_id',$id)->delete();
        Product::where('restaurant_id',$id)->delete();

        dispatch(new ESRestaurantIndex($id));

        return response()->json([
            'status' => true,
            'message' => 'Restaurant Delete'
        ]);
    }

    public function view($id)
    {
        $restaurant = $this->restaurant->findById($id);
        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant not found'
            ]);
        }

        $blade = [];
        $blade['pageTitle'] = $restaurant->name.' Menu';
        $blade['restaurant'] = $restaurant;
        $blade['restaurantId'] = $restaurant->id;

        return view('backend.restaurant.view',$blade);
    }

    public function menu(Request $request)
    {
        $blade = [];
        $blade['pageTitle'] = 'Restaurant Menu List';
        return view('backend.restaurant.menu.index', $blade);
    }

    public function xhrMenu(Request $request)
    {
        $blade = [];

        $limit = $request->has('length') ? intval($request->input('length')) : 20;

        $restaurants = $this->restaurant->relationPaginate($limit);

        $restaurants->map(function ($item) {

            $item->imageUrl = $item->logo_url ?? '';
            $item->statusText = $item->status ?'Active':'Passive';

            $item->viewUrl = route('backend.restaurant.view', $item->id);

            return $item;
        });

        $blade['draw'] = $request->input('draw');
        $blade['recordsTotal'] = $restaurants->total();
        $blade['recordsFiltered'] = $restaurants->total();
        $blade['data'] = $restaurants->toArray()['data'];
        return response()->json($blade);
    }

}
