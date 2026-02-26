<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Address;

class MyListTest extends TestCase
{
    public function test_ログイン中はいいねした商品が表示される()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $likedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'LIKED ITEM',
        ]);

        $otherItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'OTHER ITEM',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('LIKED ITEM');
        $response->assertDontSee('OTHER ITEM');
    }

    public function test_購入済み商品は_sold_と表示される()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'SOLD ITEM',
        ]);

        Like::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $address = Address::factory()->create([
            'user_id' => $buyer->id,
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);

        $response = $this->actingAs($buyer)->get('/?tab=mylist');

        $response->assertSee('Sold');
    }

    public function test_未認証の場合は何も表示されない()
    {
         $seller = User::factory()->create();

        Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'ITEM',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('ITEM');
    }
}
