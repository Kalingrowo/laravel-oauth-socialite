<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            $authUser = User::firstOrCreate(
                    [
                        'provider' => $provider,
                        'provider_id' => $user->id
                    ],
                    [
                        'name' => $user->name,
                        'email' => !empty($user->email) ? $user->email : '',
                    ]
                );
            
            Auth::login($authUser, true);
            return redirect()->to('/home');
            // $user->token;
        } catch (\Throwable $th) {
            return redirect()->to('/login');
        }
    }

    public function apiLogin()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $authToken =  $user->createToken('sanctum-test')->plainTextToken;
            
            return response()->json([
                'token' => $authToken
            ], 200);
        } else {
            return response()->json([
                'error' => 'Unauthorised'
            ], 401);
        }
    }
}
