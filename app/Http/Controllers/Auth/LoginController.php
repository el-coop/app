<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Auth;
use Carbon\Carbon;
use Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller {
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
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request) {
        return $this->guard()->attempt(
            $this->credentials($request), true
        );
    }

    protected function sendLoginResponse(Request $request) {

        $rememberTokenName = Auth::getRecallerName();

        $cookieJar = $this->guard()->getCookieJar();

        $cookieValue = $cookieJar->queued($rememberTokenName)->getValue();
        $cookieJar->queue($rememberTokenName, $cookieValue, config('auth.duration'));
        $cookieJar->queue("{$rememberTokenName}_expiry", Carbon::now()->addMinutes(config('auth.duration')), config('auth.duration'));

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    protected function authenticated(Request $request, $user) {
        if ($request->ajax()) {

            return response()->json([
                'auth' => auth()->check(),
                'user' => $user,
                'csrfToken' => csrf_token()
            ]);

        }
    }
}
