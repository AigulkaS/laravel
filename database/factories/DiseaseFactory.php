<?php

namespace Database\Factories;

use App\Models\Disease;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Disease>
 */
class DiseaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Disease::class;

    public function definition()
    {
        $var = random_int(1,2);
        return [
            'name' => "atak {$var}",
            'code' => random_int(101,102),
        ];
    }
}
