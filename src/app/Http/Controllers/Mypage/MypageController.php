<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class MypageController extends Controller
{
   public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'sell');
        if ($tab === 'buy') {
            
            $items = Item::whereHas('purchase', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        } else {
        
            $items = Item::where('user_id', $user->id)
                ->whereNull('buyer_id')
                ->get();
        }

        return view('mypage.mypage', compact('user', 'items', 'tab'));
    }
}
