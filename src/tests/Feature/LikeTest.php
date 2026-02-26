<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインユーザーはいいねできる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post("/items/{$item->id}/toggle-like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->likes()->count());
    }

    public function test_いいね済みの場合は解除できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->post("/items/{$item->id}/toggle-like");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
