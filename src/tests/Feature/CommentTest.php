<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインユーザーはコメントを投稿できコメント数が増える()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/item/{$item->id}/comments", [
                'comment' => 'テストコメント',
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'テストコメント',
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, $item->comments()->count());
    }

    public function test_未ログインではコメントを投稿できない()
    {
        $item = Item::factory()->create();

        $this->post("/item/{$item->id}/comments", [
            'comment' => '未ログインコメント',
        ]);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_255文字を超えるコメントはバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/comments", [
                'comment' => str_repeat('a', 256),
            ]);

        $response->assertSessionHasErrors('comment');
    }

    public function test_ログインユーザーがコメント未入力で送信するとバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/comments", [
                'comment' => '',
            ]);

        $response->assertSessionHasErrors('comment');
    }
}
