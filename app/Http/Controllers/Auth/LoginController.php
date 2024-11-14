<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function username()
    {
        return 'username';
    }

    /**
     * Log the login attempt.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function authenticated($request, $user)
    {
        // Log the successful login
        log_action('success', "User '{$user->name}' (ID: {$user->id}) successfully logged in.");
        return redirect()->intended($this->redirectTo);
    }

    /**
     * Log the logout attempt.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(\Illuminate\Http\Request $request)
    {
        // Log the logout action
        log_action('info', "User '{$request->user()->name}' (ID: {$request->user()->id}) logged out.");

        // Perform the logout operation
        $this->guard()->logout();

        // Invalidate the session and regenerate the token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
