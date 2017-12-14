<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\GoogleAuth;
use Carbon\Carbon;

use Google_Client;
use Google_Service_Urlshortener_Url;

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
    protected $redirectTo = '/home';

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')
            ->with(["access_type" => "offline", "prompt" => "consent select_account"])
            ->scopes(['openid', 'profile', 'email', 'https://www.googleapis.com/auth/urlshortener'])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();

        $auth = GoogleAuth::updateOrCreate(
            [
                'google_id' => $user->id
            ],
            [
                'token' => $user->token,
                'refresh_token' => $user->refreshToken,
                'name' => $user->name,
                'email' => $user->email,
                'expiry_time' => Carbon::now()->addSeconds($user->expiresIn)
            ]
        );

        $return = [
            'name' => $auth->name,
            'email' => $auth->email
        ];

        return redirect()->route('home')
            ->with('google_auth', $return);
    }
}
