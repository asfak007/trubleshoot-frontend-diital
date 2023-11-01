<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;

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
    public function definition()
    {


        return [
            'provider_id' => $this->faker->numberBetween(1, 10),
            'address_id' => $this->faker->numberBetween(1, 10),
            'customer_id' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected', 'progressing', 'cancelled', 'completed']),
            'coupon_id' => $this->faker->numberBetween(0, 5),
            'handyman_id' => $this->faker->numberBetween(0, 5),
            'campaign_id' => $this->faker->numberBetween(0, 5),
            'service_id' => $this->faker->numberBetween(0, 5),
            'category_id' => $this->faker->numberBetween(0, 5),
            'zone_id' => $this->faker->numberBetween(0, 5),
            'is_paid' => $this->faker->boolean,
            'payment_method' => $this->faker->randomElement(['cod', 'online']),
            'title' => $this->faker->sentence,
            'hint' => $this->faker->text,
            'metadata' => json_encode(['key' => $this->faker->word, 'value' => $this->faker->numberBetween(0, 100)]),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'total_tax' => $this->faker->randomFloat(2, 0, 100),
            'total_discount' => $this->faker->randomFloat(2, 0, 100),
            'additional_charge' => $this->faker->randomFloat(2, 0, 50),
            'is_rated' => $this->faker->boolean,
            'schedule' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

}
