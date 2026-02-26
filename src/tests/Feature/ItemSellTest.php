<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class ItemSellTest extends TestCase
{
     use RefreshDatabase;

    public function test_商品出品画面で入力した情報が保存される()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('items.store'), [
            'category_ids' => [$category->id],
            'condition'    => '新品',
            'name'         => 'テスト商品',
            'brand'        => 'テストブランド',
            'description'  => 'テスト商品の説明です',
            'price'        => 1000,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'description' => 'テスト商品の説明です',
            'price'       => 1000,
            'user_id'     => $user->id,
        ]);
    }
}
