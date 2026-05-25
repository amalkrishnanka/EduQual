<?php

namespace Database\Factories;

use App\Models\Flag;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Flag>
 */
class FlagFactory extends Factory
{
    protected $model = Flag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory(),
            'raised_by'   => User::factory(),
            'title'       => fake()->sentence(5),
            'description' => fake()->paragraph(2),
            'category'    => fake()->randomElement(array_keys(Flag::CATEGORIES)),
            'status'      => fake()->randomElement(array_keys(Flag::STATUSES)),
        ];
    }

    /**
     * Set flag as open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    /**
     * Set flag as resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'           => 'resolved',
            'resolved_by'      => User::factory()->superAdmin(),
            'resolution_notes' => fake()->paragraph(),
            'resolved_at'      => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
