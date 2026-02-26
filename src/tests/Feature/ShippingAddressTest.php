<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
     use RefreshDatabase;

    public function test_住所変更後に購入画面へ反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
        ]);

        $this->actingAs($user)
            ->post("/purchase/{$item->id}/address", [
                'address_id' => $address->id,
            ]);

        $response = $this->actingAs($user)
            ->get("/purchase/{$item->id}");

        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-1-1');
    }

    public function test_購入時に送付先住所が保存される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        session([
            "purchase_address_{$item->id}" => $address->id
        ]);

        $this->actingAs($user)
            ->post("/purchase/{$item->id}", [
                'payment_method' => 'card',
            ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);
    }
}
