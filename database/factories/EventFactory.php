<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Venue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        // Ensure that venues and users exist; otherwise, use a fallback value.
        $venue = Venue::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'title'       => $this->faker->sentence(6, true),
            'description' => $this->faker->paragraph(),
            // Generate a start date between 1 month ago and 1 month from now.
            'start_date'  => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            // Set end_date to a few hours after start_date.
            'end_date'    => function (array $attributes) {
                return $attributes['start_date']->modify('+'.rand(1, 3).' hours');
            },
            'venue_id'    => $venue ? $venue->id : 1,
            'created_by'  => $user ? $user->id : 1,
        ];
    }
}
