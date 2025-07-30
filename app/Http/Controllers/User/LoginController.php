<?php

namespace App\Http\Controllwe\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm() 
    {
        return view('frontend.login');    
    }

    public function login(Request $request)
    {
        $credentials = $request -> only('email','password');
        
        if (Auth::attemp($credentials)) {
            return redirect()->intended('/biodata');
        }

        return back()->withErrord([
            'email'=>'Email atau password salah',
        ])->withInput();
        
    }
}
