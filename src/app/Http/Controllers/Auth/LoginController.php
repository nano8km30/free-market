<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(){
        return view('auth.login');
    }

    
    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (!Auth::user()->profile_completed) {
                return redirect()->route('mypage.profile');
            }

            return redirect()->route('mypage');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません',
        ])->withInput();
    }
}
