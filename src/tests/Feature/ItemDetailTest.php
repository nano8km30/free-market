<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    public function test_商品詳細ページにすべての情報が表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => '商品説明です',
            'condition' => '新品',
        ]);

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'body' => 'コメント内容',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('1000');
        $response->assertSee('商品説明です');
        $response->assertSee('新品');
        $response->assertSee('コメント内容');
    }

    public function test_複数選択されたカテゴリが表示される()
    {
        $item = Item::factory()->create();

        $categories = Category::factory()->count(2)->create();

        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get('/item/' . $item->id);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_コメントとユーザー情報が表示される()
    {
        $user = User::factory()->create(['name' => 'コメント太郎']);
        $item = Item::factory()->create();

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'body' => 'コメント内容',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertSee('コメント太郎');
        $response->assertSee('コメント内容');
    }
}
