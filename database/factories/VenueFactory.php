<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition()
    {
        return [
            'name'     => $this->faker->company,  // Random company name as venue name.
            'address'  => $this->faker->address,  // Generates a fake address.
            'capacity' => $this->faker->numberBetween(50, 1000),  // Random capacity between 50 and 1000.
        ];
    }
}
