<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        if(Auth::attempt($request->only('id', 'password'))) {
            return redirect()->route('redirect');
        }
        return redirect()->route('login')->with('error', 'Id atau Password Salah');
    }
}
