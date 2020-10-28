<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialMediaAccount;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Auth;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect()->to('/home');
        // $user->token;
    }

    public function findOrCreateUser($user, $provider)
    {
        DB::beginTransaction();
        try {
            $authUser = User::whereHas('getSocialMediaAccount', function ($q) use ($user) {
                    $q->where('provider_id', $user->id);
                })
                ->first();

            if (!$authUser) {
                $authUser = User::create([
                    'name' => $user->name,
                    'email' => !empty($user->email) ? $user->email : '',
                ]);
    
                $newProvider = SocialMediaAccount::create([
                    'user_id' => $authUser->id,
                    'provider' => $provider,
                    'provider_id' => $user->id
                ]);
            }

            DB::commit();
            return $authUser;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 'error : ' . $th->getMessage();
        }
    }
}
