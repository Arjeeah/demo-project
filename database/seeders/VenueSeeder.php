<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run()
    {
        // Create 20 fake venues.
        Venue::factory()->count(20)->create();
    }
}
