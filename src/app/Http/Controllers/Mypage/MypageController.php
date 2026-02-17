<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $items = Item::where('buyer_id', $user->id)->get();
        } else {
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('mypage.mypage', compact('user', 'items', 'tab'));
    }
}
