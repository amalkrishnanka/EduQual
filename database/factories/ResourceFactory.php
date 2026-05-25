<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Resource>
 */
class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(4),
            'author'      => fake()->name(),
            'publisher'   => fake()->company(),
            'isbn'        => fake()->unique()->isbn13(),
            'type'        => fake()->randomElement(array_keys(Resource::TYPES)),
            'subject'     => fake()->randomElement(Resource::SUBJECTS),
            'grade_level' => fake()->randomElement(Resource::GRADE_LEVELS),
            'language'    => 'English',
            'edition'     => fake()->optional(0.5)->randomElement(['1st', '2nd', '3rd', '4th', '5th']),
            'description' => fake()->paragraph(3),
            'created_by'  => User::factory(),
        ];
    }
}
