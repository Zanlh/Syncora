<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AgentAuthController extends Controller
{
  //
  public function login()
  {
    return view('content.authentications.auth-login-basic');
  }

  public function loginSubmit(Request $request)
  {
    $credentials = $request->only('email', 'password');

    if (auth()->guard('agent')->attempt($credentials)) {
      return redirect()->route('agent.dashboard');
    }

    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function register()
  {
    return view('content.authentications.auth-register-basic');
  }

  public function registerSubmit(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:agents',
      'password' => 'required|string|min:8',
    ]);

    $agent = \App\Models\Agent::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
    ]);

    Auth::guard('agent')->login($agent);

    return redirect()->route('agent.dashboard');
  }

  public function logout(Request $request)
  {
    Auth::guard('agent')->logout();
    $request->session()->invalidate();    // Invalidate the current session
    $request->session()->regenerateToken(); // Generate a new session token
    return redirect()->route('agent.login');
  }
}