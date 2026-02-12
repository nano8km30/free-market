<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        return view('purchase.show', compact('item'));
    }
}
