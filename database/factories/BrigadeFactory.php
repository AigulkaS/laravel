<?php

namespace Database\Factories;

use App\Models\Brigade;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brigade>
 */
class BrigadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Brigade::class;

    public function definition()
    {
        return [
            'hospital_id' => Hospital::get()->random()->id,
            'name' => random_int(101, 130),
        ];
    }
}
