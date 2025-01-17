<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'table_id' => Table::factory(),
            'customer_id' => Customer::factory(),
            'from_time' => $this->faker->dateTimeBetween('now', '+1 hour'),
            'to_time' => $this->faker->dateTimeBetween('+1 hour', '+2 hours'),
        ];
    }
}
