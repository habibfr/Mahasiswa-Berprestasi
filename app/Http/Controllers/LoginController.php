<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  public function authenticate(Request $request): RedirectResponse
  {
    $credentials = $request->validate([
      'nik' => ['required'],
      'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      return redirect()
        ->intended('peringkat')
        ->with('login_success', 'Login successful!');
    }

    return back()->with('loginError', 'Login gagal!');
  }
}
