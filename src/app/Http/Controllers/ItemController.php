<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // タブ
        $tab = $request->get('tab', 'recommend'); 

        if ($tab === 'mylist' && Auth::check()) {
            // マイリスト
            $query = Auth::user()->likes()->with('item');

            if ($request->filled('q')) {
                $query->whereHas('item', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%');
                });
            }

            $items = $query->get()->pluck('item');

        } else {
            // おすすめ
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
}
