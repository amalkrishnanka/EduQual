<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assessment>
 */
class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['draft', 'submitted', 'locked']);

        return [
            'resource_id'   => Resource::factory(),
            'reviewer_id'   => User::factory()->reviewer(),
            'status'        => $status,
            'overall_score' => $status !== 'draft' ? fake()->randomFloat(2, 4, 10) : null,
            'submitted_at'  => $status !== 'draft' ? fake()->dateTimeBetween('-3 months', 'now') : null,
        ];
    }

    /**
     * Set assessment as draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'        => 'draft',
            'overall_score' => null,
            'submitted_at'  => null,
        ]);
    }

    /**
     * Set assessment as submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'        => 'submitted',
            'overall_score' => fake()->randomFloat(2, 4, 10),
            'submitted_at'  => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }
}
