<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sponsor;

class SponsorSeeder extends Seeder
{
    public function run()
    {
        // Create 20 fake sponsors.
        Sponsor::factory()->count(20)->create();
    }
}
