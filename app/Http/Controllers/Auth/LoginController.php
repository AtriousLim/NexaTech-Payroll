<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display the login page.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Admin login.
     */
    public function adminLogin(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::guard('admin')->attempt([
    'email' => $request->email,
    'password' => $request->password,
], $request->boolean('remember'))) {

    $request->session()->regenerate();

    return redirect()->route('admin.dashboard');
}

return back()->withErrors([
    'email' => 'Invalid email or password.'
]);
}

    /**
     * Employee login (placeholder).
     */
    public function employeeLogin(Request $request)
    {
        return back()->with(
            'info',
            'Employee login will be implemented in the next module.'
        );
    }

    /**
     * Logout.
     */
        public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}