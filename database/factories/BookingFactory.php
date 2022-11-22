<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Brigade;
use App\Models\Disease;
use App\Models\Hospital;
use App\Models\HospitalRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'status' => random_int(0,2),
            'surgeon_id' => User::get()->random()->id,
            'cardiologist_id' => User::get()->random()->id,
            'dispatcher_id' => User::get()->random()->id,
            'room_id' => HospitalRoom::get()->random()->id,
            'hospital_id' => Hospital::get()->random()->id,
            'disease_id' => Disease::get()->random()->id,
            'brigade' => Brigade::get()->random()->name,
            'start_time' => fake()->date(),
            'end_time' => fake()->date(),
            
        ];
    }
}
