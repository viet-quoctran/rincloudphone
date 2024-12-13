<?php

namespace App\Http\Controllers\Admin\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            if (Auth::user()->is_admin) {
                return redirect('/admin/clients');
            }

            Auth::logout();
            return redirect('/admin/login')->with('error', 'Bạn không có quyền Admin.');
        }

        return redirect('/admin/login')->with('error', 'Email hoặc mật khẩu không đúng.');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Đã đăng xuất.');
    }
}
