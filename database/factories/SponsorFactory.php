<?php

namespace Database\Factories;

use App\Models\Sponsor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SponsorFactory extends Factory
{
    protected $model = Sponsor::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->company,
            'description' => $this->faker->sentence(10),
            'logo_url'    => $this->faker->imageUrl(200, 200, 'business', true, 'Faker'),
        ];
    }
}
//         