<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentScore;
use App\Models\Criterion;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviewerIds = [2, 3, 4]; // Sarah, James, Priya
        $criteria    = Criterion::orderBy('sort_order')->get();

        // Justification templates per criterion
        $justifications = [
            'Accuracy' => [
                'Content is factually correct and well-researched with proper citations throughout.',
                'Minor factual inconsistencies noted in chapters 4 and 7, but overall reliable.',
                'Excellent accuracy with up-to-date references and peer-reviewed sources.',
                'Some outdated statistics referenced; most core content remains accurate.',
                'Highly accurate content verified against multiple authoritative sources.',
                'A few errors in problem solutions appendix, but theory sections are solid.',
                'Impeccable factual precision with thorough cross-referencing.',
            ],
            'Relevance' => [
                'Strongly aligned with current curriculum standards and learning outcomes.',
                'Good alignment with state standards; some supplementary material goes beyond scope.',
                'Directly maps to the prescribed syllabus with clear learning objective markers.',
                'Mostly relevant, though a few chapters cover topics outside the target curriculum.',
                'Excellent curriculum alignment with standards mapping in each chapter.',
                'Content is highly relevant to the target grade level and subject area.',
                'Some sections could be better tailored to the specific curriculum framework.',
            ],
            'Readability' => [
                'Clear, well-structured prose with appropriate vocabulary for the target audience.',
                'Excellent use of headings, summaries, and visual aids to enhance comprehension.',
                'Text could benefit from simpler sentence structures in technical sections.',
                'Very readable with effective use of examples and analogies.',
                'Well-organised with logical progression; glossary terms clearly defined.',
                'Some chapters are dense and could use more subheadings and break-out boxes.',
                'Outstanding clarity with excellent visual layout and typography.',
            ],
            'Engagement' => [
                'Includes compelling real-world examples and case studies that maintain interest.',
                'Interactive elements and discussion questions effectively engage the reader.',
                'Could use more visual elements and hands-on activities to boost engagement.',
                'Excellent use of storytelling and historical anecdotes to contextualise content.',
                'Good variety of exercises, though some repetition in later chapters.',
                'Highly engaging with multimedia companion resources referenced throughout.',
                'Adequate engagement level; more diverse activity types would strengthen this.',
            ],
            'Pedagogical Value' => [
                'Strong pedagogical framework with scaffolded learning and formative assessments.',
                'Excellent progression from foundational concepts to advanced applications.',
                'Good mix of theory and practice, with effective review sections at chapter ends.',
                'Bloom\'s taxonomy levels well represented across exercises and assessments.',
                'Differentiated instruction opportunities are well integrated throughout.',
                'Assessment rubrics and learning checklists add significant pedagogical value.',
                'Solid teaching methodology though inquiry-based approaches could be strengthened.',
            ],
        ];

        // Build assessment assignments: reviewer → resource pairs
        $assignments = [];

        // Each reviewer assesses 10-12 resources for 30+ total assessments
        foreach ($reviewerIds as $reviewerIndex => $reviewerId) {
            // Each reviewer gets a spread of resources
            $startResource = ($reviewerIndex * 3) + 1;
            for ($i = 0; $i < 12; $i++) {
                $resourceId = (($startResource + $i - 1) % 25) + 1;
                $key = "{$reviewerId}-{$resourceId}";
                if (! isset($assignments[$key])) {
                    $assignments[$key] = [
                        'reviewer_id' => $reviewerId,
                        'resource_id' => $resourceId,
                    ];
                }
            }
        }

        $assessmentCount = 0;

        foreach ($assignments as $assignment) {
            $assessmentCount++;

            // Make ~75% submitted, ~15% draft, ~10% locked
            if ($assessmentCount % 7 === 0) {
                $status = 'draft';
            } elseif ($assessmentCount % 10 === 0) {
                $status = 'locked';
            } else {
                $status = 'submitted';
            }

            $assessment = Assessment::create([
                'resource_id'  => $assignment['resource_id'],
                'reviewer_id'  => $assignment['reviewer_id'],
                'status'       => $status,
                'overall_score' => null,
                'submitted_at' => $status !== 'draft'
                    ? now()->subDays(rand(1, 90))
                    : null,
            ]);

            // Only create scores for non-draft assessments
            if ($status !== 'draft') {
                $weightedSum  = 0;
                $totalWeight  = 0;

                foreach ($criteria as $criterion) {
                    // Realistic scores: mostly 6-9, occasionally 5 or 10
                    $score = $this->generateRealisticScore();

                    $criterionJustifications = $justifications[$criterion->name] ?? $justifications['Accuracy'];
                    $justification = $criterionJustifications[array_rand($criterionJustifications)];

                    AssessmentScore::create([
                        'assessment_id' => $assessment->id,
                        'criterion_id'  => $criterion->id,
                        'score'         => $score,
                        'justification' => $justification,
                    ]);

                    $weightedSum += $score * (float) $criterion->weight;
                    $totalWeight += (float) $criterion->weight;
                }

                // Compute and save overall score
                $overallScore = $totalWeight > 0
                    ? round($weightedSum / $totalWeight, 2)
                    : 0;

                $assessment->update(['overall_score' => $overallScore]);
            }
        }
    }

    /**
     * Generate a realistic score weighted towards 6-9.
     */
    private function generateRealisticScore(): int
    {
        $rand = rand(1, 100);

        return match (true) {
            $rand <= 5   => rand(3, 4),   //  5% chance of low score
            $rand <= 15  => 5,            // 10% chance of 5
            $rand <= 35  => 6,            // 20% chance of 6
            $rand <= 55  => 7,            // 20% chance of 7
            $rand <= 75  => 8,            // 20% chance of 8
            $rand <= 90  => 9,            // 15% chance of 9
            default      => 10,           // 10% chance of 10
        };
    }
}
