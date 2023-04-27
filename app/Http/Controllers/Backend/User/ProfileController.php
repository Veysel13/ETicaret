<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    private $user;
    public function __construct(UserInterface $user)
    {
        $this->user=$user;
    }

    public function password(Request $request){
        $blade=[];
        $blade['pageTitle']='Password Change';
        return view('backend.user.profile.password',$blade);
    }

    public function passwordChange(Request $request){

        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->input('old_password'), auth('backend')->user()->password)) {
            return response()->json([
                'status' => false,
                'errors' => ['Your password is incorrect']
            ],500);
        }

        $this->user->update(auth('backend')->user()->id,['password'=>Hash::make($request->input('password'))]);

        session()->flash('success','Password Change');
        return response()->json([
            'status' => true,
            'redirectUrl' => route('backend.user.password')
        ]);
    }
}
