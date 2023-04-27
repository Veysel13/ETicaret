<?php


namespace App\Http\Controllers\Backend\Auth;

use App\Constants\AuthorityType;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\Concerns\Has;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'backend/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
        return \Auth::guard('backend');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('backend.auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request,array(
            "email"=>"required|email",
            "password"=>"required|min:5|max:15",
        ));

        $password=Hash::make($request->input('password'));

        $credentials = array('email' => $request->email, 'password' => $request->$password);
        if (auth('backend')->attempt($credentials,$request->has("remember"))){
            $request->session()->regenerate();

            return redirect()->intended('/backend/dashboard');
        }else{
            $errors=["email"=>"Error Login"];
            return back()->withErrors($errors);
        }
    }

//    protected function credentials(Request $request)
//    {
//        $credentials = $request->only($this->username(), 'password');
//        $credentials['status'] = 1;
//        $credentials['type'] = AuthorityType::BACKEND;
//
//        return $credentials;
//    }

    public function logout(Request $request)
    {   //$this->logout($request);
        Session::flush();
        Auth::logout();
        return redirect()->route('backend.auth.login');
    }
}
