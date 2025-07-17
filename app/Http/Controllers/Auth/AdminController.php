<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect('admin/dashboard');
            }
            return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while logging in'])->withInput($request->only('email'));
            //throw $th;
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function changePassword(Request $req)
    {
        $req->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ((Hash::check($req->current_password, Auth::user()->password)) == false) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        Auth::user()->update(['password' => bcrypt($req->new_password)]);
        Auth::logout();
        return redirect()->route('login')->with('status', 'Password changed successfully, please login again');
    }
}
