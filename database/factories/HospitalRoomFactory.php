<?php

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\HospitalRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HospitalRoom>
 */
class HospitalRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = HospitalRoom::class;

    public function definition()
    {
        return [
            'hospital_id' => Hospital::get()->random()->id,
            'name' => random_int(100, 103)
        ];
    }
}
