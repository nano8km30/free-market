<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'recommend'); 

        if ($tab === 'mylist' && Auth::check()) {
            $query = Auth::user()->likes()->with('item');

            if ($request->filled('q')) {
                $query->whereHas('item', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%');
                });
            }

            $items = $query->get()->pluck('item');
        } else {
            $query = Item::query();

            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            if ($request->filled('q')) {
                $query->where('name', 'like', '%' . $request->q . '%');
            }

            $items = $query->latest()->get();
        }

        return view('items.index', compact('items', 'tab'));
    }


    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        $likesCount = $item->likes->count();

        $liked = Auth::check() ? $item->likes()->where('user_id', Auth::id())->exists() : false;

        return view('items.show', compact('item', 'likesCount', 'liked'));
    }

    public function toggleLike($item_id, Request $request)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        
        $like = $item->likes()->where('user_id', $user->id)->first();
        
        if ($like) {
            $like->delete();  
            $liked = false;
        } else {
            $item->likes()->create([
                'user_id' => $user->id
            ]); 
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'like_count' => $item->likes->count(),
            'liked' => $liked,
        ]);
    }
}
