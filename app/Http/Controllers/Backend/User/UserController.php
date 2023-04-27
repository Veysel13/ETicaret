<?php


namespace App\Http\Controllers\Backend\User;


use App\Constants\AuthorityType;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $blade = [];
        $blade['pageTitle'] = 'User list';
        return view('backend.user.index', $blade);
    }

    public function xhrIndex(Request $request)
    {

        $blade = [];

        $limit = $request->has('length') ? intval($request->input('length')) : 20;

        $users = $this->user->paginate($limit);
        $users->map(function ($item) {

            $item->statusText = $item->status == 1 ? 'Active' : 'Passive';

            $item->editUrl = route('backend.user.edit', $item->id);

            $item->removeUrl = route('backend.user.delete', $item->id);

            return $item;
        });

        $blade['draw'] = $request->input('draw');
        $blade['recordsTotal'] = $users->total();
        $blade['recordsFiltered'] = $users->total();
        $blade['data'] = $users->toArray()['data'];
        return response()->json($blade);
    }

    public function create(Request $request)
    {
        $blade = [];
        $blade['pageTitle'] = 'New User';
        $blade['authorityLists'] = AuthorityType::authorityList;
        return view('backend.user.create', $blade);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'string|required',
            'email' => 'email|required|unique:users,email',
            'status' => 'required',
            'password' => 'required|confirmed'
        ]);

        $data = [
            'type' => 0,
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'status' => $request->input('status', 0),
            'password' => Hash::make($request->input('password',)),
        ];

        $this->user->create($data);

        session()->flash('success', 'New user added successfully');
        return response()->json([
            'status'=>true,
            'redirectUrl'=>route('backend.user')
        ]);
    }

    public function edit($id)
    {

        $user = $this->user->findById($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'errors' => ['User Not Found']
            ], 500);
        }

        $blade = [];
        $blade['pageTitle'] = 'Update User';
        $blade['user'] = $user;

        $content = \View::make('backend.user.edit', $blade);
        return response()->json([
            'status' => true,
            'content' => $content->render()
        ]);
    }

    public function update($id, Request $request)
    {

        $user = $this->user->findById($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'errors' => ['User Not Found']
            ], 500);
        }

        $request->validate([
            'fullname' => 'string|required',
            'email' => 'email|required|unique:users,email,' . $user->id,
            'status' => 'required',
            'password' => 'confirmed'
        ]);

        $data = [
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'status' => $request->input('status', 0),
        ];

        if ($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $this->user->update($user->id, $data);

        return response()->json([
            'status' => true,
            'message' => 'User Update',
        ]);
    }

    public function delete($id)
    {

        $this->user->remove($id);

        return response()->json([
            'status' => true,
            'message' => 'User Delete'
        ]);
    }
}
