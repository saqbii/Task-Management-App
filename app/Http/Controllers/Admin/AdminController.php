<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function getLogin(){
        return view('admin.login');
    }

    public function adminLogin()
    {
        // Validate the submitted form data
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('admin.login')->with('error', 'You do not have permission to access the admin dashboard.');
            }
        }

        return redirect()->route('admin.login')->with('error', 'Invalid email or password.');
    }

    public function dashboard(){
        return view('admin.dashboard');
    }
}
