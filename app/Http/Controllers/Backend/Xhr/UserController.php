<?php


namespace App\Http\Controllers\Backend\Xhr;


use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private $user;
    public function __construct(UserInterface $user)
    {
        $this->user=$user;
    }

    public function users(Request $request){

        $result = [];
        $users = $this->user->all();
        $items = [];
        foreach ($users as $user) {
            array_push($items, [
                'id' => $user->id,
                'text' => $user->fullname
            ]);
        }
        $result['users'] = $items;
        return response()->json($result);
    }

    public function statusUpdate(Request $request)
    {
        $refId = $request->input('refId');
        $newId = $request->input('newId');

        $userData = $this->user->findById($refId);
        if (!$userData) {
            return response()->json([
                'status' => false,
                'message' => 'User Not Found'
            ]);
        }

        $this->user->update($refId,['status' => $newId]);

        return response()->json([
            'status' => true,
            'message' => 'User Update'
        ]);
    }
}
