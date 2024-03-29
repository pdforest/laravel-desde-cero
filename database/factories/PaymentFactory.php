<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat($maxDecimals=2, $min=15, $max=500),
            'payed_at' => $this->faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = 'America/Argentina/Buenos_Aires'),
            //'order_id',
        ];
    }
}
