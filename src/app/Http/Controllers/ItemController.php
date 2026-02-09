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

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer',
            'condition' => 'required|string',
            'price' => 'required|integer',
            'image' => 'required|image|max:2048',
        ]);

        // 画像を storage/app/public/items に保存
        $path = $request->file('image')->store('items', 'public');

        Item::create([
            'user_id' => Auth::id(),
            'category_ids' => $validated['category_ids'],
            'name' => $validated['name'],
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'image' => $path,
            'is_sold' => 0,
        ]);

        return redirect()->route('items.index');
    }
}
