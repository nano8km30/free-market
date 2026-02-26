<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

    class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_支払い方法選択が小計画面に反映される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'price' => 1000,
        ]);

        $this->actingAs($user);

        $response = $this->post(
            route('purchase.payment.store', $item->id),
            [
                'payment_method' => 'card',
            ]
        );

        $response->assertRedirect(
            route('purchase.confirm', $item->id)
        );

        $response = $this->get(
            route('purchase.confirm', $item->id)
        );

        $response->assertSee('card');
    }
}

