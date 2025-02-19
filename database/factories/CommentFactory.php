<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Event; // We'll use Event as a default commentable type
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        // Choose a random Event and User; ensure at least one exists.
        $event = Event::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'user_id'          => $user ? $user->id : 1,
            'content'          => $this->faker->paragraph(),
            'commentable_type' => 'App\Models\Event', // Default to Event; change as needed.
            'commentable_id'   => $event ? $event->id : 1,
        ];
    }
}
