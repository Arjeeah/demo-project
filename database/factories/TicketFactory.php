<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        // Pick a random event and user; ensure at least one exists.
        $event = Event::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'event_id'      => $event ? $event->id : 1,
            'user_id'       => $user ? $user->id : 1,
            'seat_number'   => $this->faker->bothify('Seat-##??'),
            'purchase_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
