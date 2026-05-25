<?php

namespace Database\Seeders;

use App\Models\Criterion;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'name'        => 'Accuracy',
                'description' => 'Factual correctness and precision of content',
                'weight'      => 0.2500,
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Relevance',
                'description' => 'Alignment with curriculum standards and learning objectives',
                'weight'      => 0.2000,
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Readability',
                'description' => 'Clarity, structure, and accessibility of text',
                'weight'      => 0.2000,
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Engagement',
                'description' => 'Ability to capture and maintain learner interest',
                'weight'      => 0.1500,
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Pedagogical Value',
                'description' => 'Effectiveness of teaching methodology and exercises',
                'weight'      => 0.2000,
                'sort_order'  => 5,
            ],
        ];

        foreach ($criteria as $criterion) {
            Criterion::create(array_merge($criterion, [
                'is_active' => true,
            ]));
        }
    }
}
