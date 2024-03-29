<?php

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hospital>
 */
class HospitalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Hospital::class;

    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'short_name' => $this->faker->title,
            'address' => $this->faker->address,
            'geo_lat' => $this->faker->latitude,
            'geo_lon' => $this->faker->longitude,
        ];
    }
}
