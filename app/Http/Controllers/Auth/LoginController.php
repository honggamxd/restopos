<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function attemptLogin(Request $request)
    {
        $user = User::where('username',$request->username)->first();
        if($user!=null){
            $password = $user->is_valid == 1 ? $request->password : 'wrongest password ever do not try this';
        }else{
            $password = $request->password;
        }
        $new_credentials = [
            'username' => $request->username,
            'password' => md5($password),
        ];
        return $this->guard()->attempt(
            $new_credentials, $request->has('remember')
        );
    }
    public function username()
    {
        return 'username';
    }
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
