<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    DB::table('activity_logs')->insert([
        'admin_id' => Auth::guard('admin')->id(),
        'action' => 'Logged in.',
        'created_at' => now(),
    ]);

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
        $adminId = Auth::guard('admin')->id();

        if ($adminId) {
            DB::table('activity_logs')->insert([
                'admin_id' => $adminId,
                'action' => 'Logged out.',
                'created_at' => now(),
            ]);
        }

        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
