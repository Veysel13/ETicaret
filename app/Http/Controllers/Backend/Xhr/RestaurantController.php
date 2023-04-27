<?php


namespace App\Http\Controllers\Backend\Xhr;


use App\Constants\AuthorityType;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Repositories\Restaurant\RestaurantInterface;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{

    private $restaurant;
    public function __construct(RestaurantInterface $restaurant)
    {
        $this->restaurant=$restaurant;
    }

    public function restaurants(Request $request){

        $result = [];

        $restaurants =  Restaurant::filter(request())
            ->where('status',1)
            ->orderBy('created_at')
            ->take(100)->get();

        $items = [];
        foreach ($restaurants as $restaurant) {
            array_push($items, [
                'id' => $restaurant->id,
                'text' => $restaurant->name
            ]);
        }

        $result['restaurants'] = $items;
        return response()->json($result);
    }

    public function statusUpdate(Request $request)
    {
        $refId = $request->input('refId');
        $newId = $request->input('newId');

        $storeData = $this->restaurant->findById($refId);
        if (!$storeData) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant Not Found'
            ]);
        }

        $this->restaurant->update($refId,['status' => $newId]);

        return response()->json([
            'status' => true,
            'message' => 'Restaurant Update'
        ]);
    }
}
