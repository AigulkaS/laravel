<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Brigade;
use App\Models\Disease;
use App\Models\Hospital;
use App\Models\HospitalRoom;
use App\Models\PatientCondition;
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
            'hospital_id' => Hospital::get()->random()->id,
            'room_id' => HospitalRoom::get()->random()->id,
            'surgeon_id' => User::get()->random()->id,
            'cardiologist_id' => User::get()->random()->id,
            'user_id' => User::get()->random()->id,
            'disease_id' => Disease::get()->random()->id,
            'condition_id' => PatientCondition::get()->random()->id,
            'date_time' => fake()->dateTime(),
        ];
    }
}
