<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = auth()->user();
        $addressId = session('purchase_address_' . $item->id);
        $address = $addressId
            ? Address::find($addressId)
            : Address::where('user_id', $user->id)->first();

        return view('purchase.show', compact('item', 'user', 'address', 'addressId'));
    }

    public function store(Request $request, Item $item)
    {
        $request->validate([
            'payment_method' => 'required',
        ]);

        if ($item->buyer_id) {
            return back()->with('error', 'この商品はすでに購入されています');
        }

        if ($item->user_id === auth()->id()) {
            abort(403);
        }

        $addressId = session('purchase_address_' . $item->id);
        if (!$addressId) {
            return back()->withErrors(['address' => '配送先を選択してください']);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentType = $request->payment_method === 'convenience' ? 'konbini' : 'card';

        $session = StripeSession::create([
            'payment_method_types' => [$paymentType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/'), 
            'cancel_url' => url('/purchase/' . $item->id),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => auth()->id(),
                'payment_method' => $request->payment_method,
            ],
        ]);

         $item->update([
            'buyer_id' => auth()->id(), 
            'payment_method' => $request->payment_method, 
            'is_sold' => true, 
        ]);

        return redirect($session->url);
    }

    public function editAddress($id)
    {
        $item = Item::findOrFail($id);
        $address = auth()->user()->addresses()->first();

        return view('purchase.address', compact('item', 'address'));
    }

    public function updateAddress(Request $request, $itemId)
    {
        $request->validate([
            'postal_code' => 'required',
            'address'     => 'required',
            'building'    => 'nullable',
        ]);

        auth()->user()->addresses()->updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only(['postal_code', 'address', 'building'])
        );

        return redirect()->route('purchase.show', $itemId);
    }
}
