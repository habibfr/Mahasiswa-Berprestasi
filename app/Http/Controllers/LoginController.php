<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function authenticate(Request $request):RedirectResponse {
        $credentials=$request->validate([
            'nik'=>['required'],
            'password'=>['required'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session->regenerate();

            return redirect()->intended('kriteria');
        }

        return back()->withErrors([
            'nik'=>'The provided credentials do not match our records.'
        ]);
    }
}
