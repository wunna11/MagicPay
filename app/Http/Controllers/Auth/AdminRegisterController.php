<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin_user');
    }

    public function showRegisterForm()
    {
        return view('auth.admin_register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:admin_users',
            'phone' => 'required|unique:admin_users',
            'password' => 'required',
        ]);
        
        if ($validated) 
        {
            $admin_user = new AdminUser();
            $admin_user->name = request('name');
            $admin_user->email = request('email');
            $admin_user->phone = request('phone');
            $admin_user->password = Hash::make(request('password'));
            $admin_user->save();

            return redirect()->route('admin.home');
        }
        return back()->withErrors($validated);
    }
}
