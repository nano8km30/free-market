<?php

namespace App\Http\Controllers\Mypage;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    // プロフィール編集画面表示
    public function edit()
    {
        $user = auth()->user();
        $address = $user->address; // リレーションで住所取得（1対1）

        return view('mypage.profile', compact('user', 'address'));
    }

    // プロフィール更新処理
    public function update(Request $request)
    {
        $user = auth()->user();

        // バリデーション（例）
        $request->validate([
            'name' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        // ユーザー情報更新（名前など）
        $user->update([
            'name' => $request->name,
        ]);

        // 住所情報更新
        $address = $user->address ?? new Address();
        $address->user_id = $user->id;
        $address->postal_code = $request->postcode; 
        $address->address = $request->address;
        $address->building = $request->building;
        $address->save();

        return redirect()->route('items.index');
    }
}
