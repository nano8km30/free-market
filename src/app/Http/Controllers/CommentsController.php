<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
        'body' => 'required|string|max:1000', 
    ]);

    $item->comments()->create([
        'user_id' => Auth::id(),
        'body' => $request->body,
    ]);

    return redirect()->route('items.show', $item);
    }
}