<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品ページを開くとすべての商品が表示される()
    {
        Item::factory()->create(['name' => 'ITEM A']);
        Item::factory()->create(['name' => 'ITEM B']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ITEM A');
        $response->assertSee('ITEM B');
    }

    public function test_購入済み商品には_sold_ラベルが表示される()
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'SOLD ITEM',
        ]);

        $address = Address::create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-2-3',
            'building' => 'テストビル',
        ]);

        DB::table('purchases')->insert([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => 'card',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    public function test_ログイン中は自分が出品した商品が一覧に表示されない()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'MY ITEM',
        ]);

        Item::factory()->create([
            'name' => 'OTHER ITEM',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee('MY ITEM');
        $response->assertSee('OTHER ITEM');
    }
}