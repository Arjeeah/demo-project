<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    public function run()
    {
        // Generate 50 fake comments.
        Comment::factory()->count(50)->create();
    }
}
