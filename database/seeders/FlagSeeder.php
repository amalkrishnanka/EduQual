<?php

namespace Database\Seeders;

use App\Models\Flag;
use App\Models\FlagComment;
use Illuminate\Database\Seeder;

class FlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flags = [
            // Open flags
            [
                'resource_id'      => 1,
                'raised_by'        => 2, // Dr. Sarah Chen
                'title'            => 'Outdated integration formulas in Chapter 12',
                'description'      => 'Several integration formulas in Chapter 12 use deprecated notation that was updated in the 2023 curriculum standards. Students may be confused by the inconsistency with current teaching materials.',
                'category'         => 'outdated',
                'status'           => 'open',
                'resolved_by'      => null,
                'resolution_notes' => null,
                'resolved_at'      => null,
            ],
            [
                'resource_id'      => 5,
                'raised_by'        => 3, // Prof. James Miller
                'title'            => 'Incorrect diagram of mitochondrial membrane',
                'description'      => 'Figure 8.3 shows the inner mitochondrial membrane without cristae folds. This is a significant factual error that could mislead students about the structure of mitochondria.',
                'category'         => 'inaccurate',
                'status'           => 'open',
                'resolved_by'      => null,
                'resolution_notes' => null,
                'resolved_at'      => null,
            ],

            // Under review
            [
                'resource_id'      => 10,
                'raised_by'        => 4, // Dr. Priya Sharma
                'title'            => 'Python 2 syntax used in examples',
                'description'      => 'Multiple code examples use Python 2 print statements (print "Hello") instead of Python 3 syntax (print("Hello")). Since Python 2 has been end-of-life since 2020, this needs updating.',
                'category'         => 'outdated',
                'status'           => 'under_review',
                'resolved_by'      => null,
                'resolution_notes' => null,
                'resolved_at'      => null,
            ],
            [
                'resource_id'      => 13,
                'raised_by'        => 2,
                'title'            => 'Culturally insensitive portrayal in Chapter 5',
                'description'      => 'The depiction of indigenous civilisations in Chapter 5 relies on stereotypical narratives and lacks indigenous perspectives. Recommend consulting with cultural advisors for revision.',
                'category'         => 'inappropriate',
                'status'           => 'under_review',
                'resolved_by'      => null,
                'resolution_notes' => null,
                'resolved_at'      => null,
            ],

            // Resolved flags
            [
                'resource_id'      => 7,
                'raised_by'        => 3,
                'title'            => 'Wrong molecular weight for benzene',
                'description'      => 'Table 3.1 lists the molecular weight of benzene as 72.06 g/mol instead of the correct 78.11 g/mol. This error propagates into several practice problems.',
                'category'         => 'inaccurate',
                'status'           => 'resolved',
                'resolved_by'      => 1, // Admin
                'resolution_notes' => 'Publisher confirmed the error and issued a correction in the errata sheet. Digital version has been updated with the correct molecular weight.',
                'resolved_at'      => now()->subDays(15),
            ],
            [
                'resource_id'      => 11,
                'raised_by'        => 4,
                'title'            => 'Broken code examples in sorting chapter',
                'description'      => 'The merge sort implementation on page 245 has an off-by-one error in the merge function. The code throws an ArrayIndexOutOfBoundsException when run.',
                'category'         => 'inaccurate',
                'status'           => 'resolved',
                'resolved_by'      => 1,
                'resolution_notes' => 'Code has been corrected in the companion repository. Author will update in the 4th edition. A correction notice has been added to the course materials.',
                'resolved_at'      => now()->subDays(30),
            ],
            [
                'resource_id'      => 20,
                'raised_by'        => 2,
                'title'            => 'GDP figures from 2015 used as current data',
                'description'      => 'Chapter 9 presents 2015 GDP data as current figures. With significant economic changes since then, students get a misleading picture of the global economy.',
                'category'         => 'outdated',
                'status'           => 'resolved',
                'resolved_by'      => 1,
                'resolution_notes' => 'Updated supplementary materials with 2024 data have been provided. The publisher plans a new edition with current statistics for next academic year.',
                'resolved_at'      => now()->subDays(7),
            ],

            // Dismissed flags
            [
                'resource_id'      => 15,
                'raised_by'        => 5, // Alex Johnson (viewer)
                'title'            => 'Shakespeare language too difficult for students',
                'description'      => 'The original Elizabethan English in the Shakespeare anthology is too difficult for modern students to understand without extensive teacher support.',
                'category'         => 'other',
                'status'           => 'dismissed',
                'resolved_by'      => 1,
                'resolution_notes' => 'The resource is a scholarly reference edition intended for undergraduate study. Annotations and glossary are provided for accessibility. The original language is a feature, not a defect, for the intended audience level.',
                'resolved_at'      => now()->subDays(45),
            ],
            [
                'resource_id'      => 8,
                'raised_by'        => 6, // Maria Garcia (viewer)
                'title'            => 'Missing coverage of string theory',
                'description'      => 'The Classical Mechanics textbook does not cover string theory or advanced quantum mechanics topics.',
                'category'         => 'other',
                'status'           => 'dismissed',
                'resolved_by'      => 1,
                'resolution_notes' => 'String theory and quantum mechanics are outside the scope of a Classical Mechanics textbook. These topics are covered in separate, dedicated resources available in the library.',
                'resolved_at'      => now()->subDays(60),
            ],
        ];

        foreach ($flags as $flagData) {
            $flag = Flag::create($flagData);

            // Add comments to some flags
            if ($flag->status === 'under_review') {
                FlagComment::create([
                    'flag_id' => $flag->id,
                    'user_id' => 1, // Admin
                    'comment' => 'Thank you for reporting this issue. We are currently reviewing the content and will reach out to the publisher for clarification.',
                ]);

                FlagComment::create([
                    'flag_id' => $flag->id,
                    'user_id' => $flagData['raised_by'],
                    'comment' => 'I can provide additional specific page references and examples if that would be helpful for the review process.',
                ]);
            }

            if ($flag->status === 'resolved') {
                FlagComment::create([
                    'flag_id' => $flag->id,
                    'user_id' => 1,
                    'comment' => 'This issue has been verified and addressed. Thank you for bringing it to our attention.',
                ]);
            }
        }
    }
}
