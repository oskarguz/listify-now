<?php

namespace Database\Factories;

use App\Enum\Visibility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checklist>
 */
class ChecklistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => \Str::random(40),
            'visibility' => Visibility::Public->value
        ];
    }

    public function privateVisibility(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'visibility' => Visibility::Private->value
            ];
        });
    }
}
