<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request){
        $request->validate([
        'name' => 'required|string|max:255',
        'postal_code' => 'nullable|string|max:10',
        'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->save();

        return redirect()->back()
            ->with('success', 'プロフィールを更新しました');
    }
}
