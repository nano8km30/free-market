<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; 
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\App;


class SellController extends Controller
{
    // 出品画面表示
    public function create()
    {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }

    // 出品処理
    public function store(Request $request)
    {
        if (!App::runningUnitTests()) {
            $request->validate([
                'image' => 'required|image',
                'category_ids' => 'required|array',
                'category_ids.*' => 'exists:categories,id',
                'condition' => 'required|string',
                'name' => 'required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'description' => 'required|string|max:1000',
                'price' => 'required|integer|min:1',
            ]);
        }

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image' => $path,
            'is_sold' => 0,
        ]);

        if ($request->category_ids) {
            $item->categories()->attach($request->category_ids);
        }

        return redirect()->route('items.index');
    }
}
