<?php

namespace App\Http\Controllers\Mypage;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;

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
    public function update(ProfileRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();

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
