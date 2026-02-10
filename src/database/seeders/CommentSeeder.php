<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); 
        $item = Item::first(); 

        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'これはテストコメントです。',  
        ]);
    }
}
