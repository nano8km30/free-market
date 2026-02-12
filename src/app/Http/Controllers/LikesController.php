<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function toggleLike(Item $item, Request $request)
    {
        $user = auth()->user();
        $liked = $item->likes()->where('user_id', $user->id)->exists();

        if ($liked) {
            $item->likes()->where('user_id', $user->id)->delete();
        } else {
            $item->likes()->create(['user_id' => $user->id]);
        }

        return redirect()->back()->with('like_count', $item->likes()->count());
    }
}

