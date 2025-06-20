<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        if(Auth::attempt($request->only('name', 'password'))) {
            return redirect()->route('redirect');
        }
        return redirect()->route('login')->with('error', 'Username atau Password Salah');
    }
}
