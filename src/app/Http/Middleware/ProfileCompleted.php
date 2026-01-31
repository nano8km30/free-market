<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next){
        if (Auth::check() && !Auth::user()->profile_completed) {
            return redirect()->route('mypage.profile');
        return $next($request);
        }
    }

    public function update(Request $request){
        $user = Auth::user();

        // プロフィール保存処理...

        $user->profile_completed = true;
        $user->save();

        return redirect()->route('mypage');
    }
}
