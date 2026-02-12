<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Log;
use Stripe\Webhook;
use Stripe\Stripe;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $endpointSecret = env('STRIPE_WEBHOOK_SECRET'); 

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['status' => 'invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['status' => 'invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $itemId = $session->metadata->item_id;
            $userId = $session->metadata->user_id;
            $paymentMethod = $session->metadata->payment_method;

            $item = Item::find($itemId);
            if ($item && !$item->buyer_id) {
                $item->update([
                    'buyer_id' => $userId,
                    'payment_method' => $paymentMethod,
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
