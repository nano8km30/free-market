<?php

namespace App\Http\Controllers\Mypage;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $address = $user->address; 

        return view('mypage.profile', compact('user', 'address'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        if ($request->hasFile('avatar')) {

            if ($user->icon_image) {
                Storage::disk('public')->delete($user->icon_image);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            $user->icon_image = $path;
        }

        $user->name = $request->name;
        $user->save();

        $address = $user->address ?? new Address();
        $address->user_id = $user->id;
        $address->postal_code = $request->postcode;
        $address->address = $request->address;
        $address->building = $request->building;
        $address->save();

        return redirect()->route('items.index');
    }
}
