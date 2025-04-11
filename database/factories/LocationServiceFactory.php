<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationService>
 */
class LocationServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'venue_id' => $this->faker->uuid(),
            'service_name' => $this->faker->words(3, true), // ví dụ: "Deluxe Massage Package"
            'price' => $this->faker->randomFloat(2, 10, 1000), // từ 10 đến 1000
            'is_available' => $this->faker->boolean(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
