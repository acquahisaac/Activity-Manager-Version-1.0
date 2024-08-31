<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function adminsLoginGet()
    {
        return view('auth.admins.login');
    }

    public function adminsloginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admins')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard.home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    public function usersLoginGet()
    {
        return view('auth.users.login');
    }

    public function usersLoginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('users')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('users.dashboard.home');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function redirectAfterLogout(Request $request, $userType)
    {
        $this->logout($request);
        if ($userType === 'admin') {
            return redirect()->route('auth.admin.login');
        } elseif ($userType === 'user') {
            return redirect()->route('auth.user.login');
        }
    }

    public function adminLogout(Request $request)
    {
        return $this->redirectAfterLogout($request, 'admin');
    }

    public function userLogout(Request $request)
    {
        return $this->redirectAfterLogout($request, 'user');
    }
}
