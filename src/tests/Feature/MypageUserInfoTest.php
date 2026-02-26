<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Tests\TestCase;

class MypageUserInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_マイページでユーザー情報と商品一覧が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品',
        ]);

        $buyItem = Item::factory()->create([
            'name' => '購入商品',
            'buyer_id' => $user->id,
            'is_sold' => true,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('出品商品');
        $response->assertDontSee('購入商品');

        $response = $this->get('/mypage?tab=buy');

        $response->assertStatus(200);
        $response->assertSee('購入商品');
        $response->assertDontSee('出品商品');
    }
}
