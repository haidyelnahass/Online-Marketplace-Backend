<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, $min = 0, $max = 500),
            'quantity' => $this->faker->randomDigit(),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->url(),
            'creator_id' => User::all()->random()->id,
        ];
    }
}
