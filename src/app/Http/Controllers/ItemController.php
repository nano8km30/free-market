<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
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

    public function store(ExhibitionRequest $request)
    {
        // 画像保存
        $path = $request->file('image')->store('items', 'public');

        Item::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id, // ← 単数
            'name'        => $request->name,
            'brand'       => $request->brand,
            'description' => $request->description,
            'price'       => $request->price,
            'condition'   => $request->condition,
            'image'       => $path,
            'is_sold'     => 0,
        ]);

        return redirect()->route('items.index');
    }

    public function show($item_id)
    {
        $item = Item::with([
            'user',
            'categories',
            'comments.user',
            'likes',
        ])->findOrFail($item_id);

        return view('items.show', compact('item'));
    }
}
