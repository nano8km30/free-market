<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_商品名で部分一致検索ができる()
    {
        Item::factory()->create(['name' => 'iPhone ケース']);
        Item::factory()->create(['name' => 'Android スマホ']);
        Item::factory()->create(['name' => 'パソコン']);

        $response = $this->get('/?q=iPhone');

        $response->assertStatus(200);
        $response->assertSee('iPhone ケース');
        $response->assertDontSee('Android スマホ');
        $response->assertDontSee('パソコン');
    }

    public function test_検索状態がマイリストでも保持される()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create(['name' => 'テスト商品A']);
        $otherItem = Item::factory()->create(['name' => 'テスト商品B']);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/?tab=mylist&q=商品A');

        $response->assertStatus(200);
        $response->assertSee('テスト商品A');
        $response->assertDontSee('テスト商品B');
    }
}
