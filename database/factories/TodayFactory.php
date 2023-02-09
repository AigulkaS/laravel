<?php

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\Today;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Today>
 */
class TodayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Today::class;

    public function definition()
    {
        return [
            'date' => fake()->date(),
            'hospital_id' => Hospital::get()->random()->id,
            'surgeon_id' => User::get()->random()->id,
            'cardiologist_id' => User::get()->random()->id,
        ];
    }
}
