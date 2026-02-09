<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; 
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;

class SellController extends Controller
{
    // 出品画面表示
    public function create()
    {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }

    // 出品処理
    public function store(ExhibitionRequest $request)
    {
        $request->validate([
            'image' => 'required|image',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|integer|min:1',
        ]);

        // 画像をstorageに保存
        $path = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image' => $path,
            'category_ids' => $request->category_ids, // ← これ！
            'is_sold' => 0,
        ]);

        $item->categories()->sync($request->category_ids);

        return redirect()->route('items.index');
    }
}
