<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Table;

class TableFactory extends Factory
{
    protected $model = Table::class;

    public function definition()
    {
        return [
            'capacity' => $this->faker->numberBetween(2, 10),
        ];
    }
}
