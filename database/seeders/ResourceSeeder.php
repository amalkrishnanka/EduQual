<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            // Mathematics
            ['title' => 'Advanced Calculus: Theory and Practice', 'author' => 'Dr. Robert Stewart', 'publisher' => 'Academic Press', 'isbn' => '978-0-13-468599-1', 'type' => 'textbook', 'subject' => 'Mathematics', 'grade_level' => 'Undergraduate', 'edition' => '4th', 'description' => 'A comprehensive guide to single and multivariable calculus with emphasis on proofs and real-world applications.'],
            ['title' => 'Fundamentals of Linear Algebra', 'author' => 'Prof. Linda Zhang', 'publisher' => 'Springer', 'isbn' => '978-0-07-352932-5', 'type' => 'textbook', 'subject' => 'Mathematics', 'grade_level' => 'Undergraduate', 'edition' => '2nd', 'description' => 'Introduction to vector spaces, eigenvalues, and matrix decomposition for STEM students.'],
            ['title' => 'Mathematics for Middle School: Problem Solving', 'author' => 'Karen Phillips', 'publisher' => 'Pearson Education', 'isbn' => '978-1-59-028074-0', 'type' => 'textbook', 'subject' => 'Mathematics', 'grade_level' => '6-8', 'description' => 'A problem-based approach to pre-algebra and geometry for grades 6 through 8.'],

            // Science
            ['title' => 'Exploring Physical Science', 'author' => 'Dr. Michael Torres', 'publisher' => 'McGraw-Hill', 'isbn' => '978-0-32-191041-7', 'type' => 'textbook', 'subject' => 'Science', 'grade_level' => '6-8', 'description' => 'Engaging introduction to physics and chemistry concepts for middle school learners.'],

            // Biology
            ['title' => 'The Living Cell: A Molecular Approach', 'author' => 'Dr. Emily Watson', 'publisher' => 'Cambridge University Press', 'isbn' => '978-0-52-176956-9', 'type' => 'textbook', 'subject' => 'Biology', 'grade_level' => 'Undergraduate', 'edition' => '3rd', 'description' => 'Deep dive into cell biology, molecular genetics, and biochemistry with lab exercises.'],
            ['title' => 'Human Anatomy and Physiology', 'author' => 'Dr. Rachel Kim', 'publisher' => 'Elsevier', 'isbn' => '978-0-32-356839-7', 'type' => 'reference', 'subject' => 'Biology', 'grade_level' => 'Graduate', 'description' => 'Comprehensive reference covering all human body systems with clinical correlations.'],

            // Chemistry
            ['title' => 'Organic Chemistry: Mechanisms and Reactions', 'author' => 'Prof. David Nguyen', 'publisher' => 'Wiley', 'isbn' => '978-1-11-813937-8', 'type' => 'textbook', 'subject' => 'Chemistry', 'grade_level' => 'Undergraduate', 'edition' => '5th', 'description' => 'Mechanism-focused approach to organic chemistry with 3D molecular visualisations.'],

            // Physics
            ['title' => 'Classical Mechanics: A Modern Perspective', 'author' => 'Dr. Alan Foster', 'publisher' => 'Oxford University Press', 'isbn' => '978-0-19-880289-3', 'type' => 'textbook', 'subject' => 'Physics', 'grade_level' => 'Undergraduate', 'description' => 'Newtonian mechanics, Lagrangian formulations, and orbital dynamics.'],
            ['title' => 'Quantum Physics for Beginners', 'author' => 'Dr. Sofia Petrov', 'publisher' => 'Digital Academic', 'isbn' => '978-0-98-765432-1', 'type' => 'ebook', 'subject' => 'Physics', 'grade_level' => 'Undergraduate', 'description' => 'Accessible introduction to quantum mechanics concepts without heavy mathematics.'],

            // Computer Science
            ['title' => 'Introduction to Python Programming', 'author' => 'Prof. Jason Lee', 'publisher' => 'O\'Reilly Media', 'isbn' => '978-1-49-195019-8', 'type' => 'ebook', 'subject' => 'Computer Science', 'grade_level' => '9-10', 'description' => 'Learn Python from scratch with hands-on projects and exercises for high school students.'],
            ['title' => 'Data Structures and Algorithms in Java', 'author' => 'Dr. Anita Patel', 'publisher' => 'Addison-Wesley', 'isbn' => '978-0-13-284737-7', 'type' => 'textbook', 'subject' => 'Computer Science', 'grade_level' => 'Undergraduate', 'edition' => '3rd', 'description' => 'Essential data structures and algorithmic strategies with Java implementations.'],
            ['title' => 'Web Development Fundamentals', 'author' => 'Chris Rodriguez', 'publisher' => 'Packt Publishing', 'isbn' => '978-1-78-913274-5', 'type' => 'ebook', 'subject' => 'Computer Science', 'grade_level' => '11-12', 'description' => 'HTML, CSS, and JavaScript essentials for building modern web applications.'],

            // History
            ['title' => 'World History: Civilizations Past and Present', 'author' => 'Prof. Margaret Thompson', 'publisher' => 'Cengage', 'isbn' => '978-1-30-595958-4', 'type' => 'textbook', 'subject' => 'History', 'grade_level' => '9-10', 'edition' => '7th', 'description' => 'Survey of world civilisations from antiquity to the modern era with primary source documents.'],
            ['title' => 'The American Revolution: A Documentary History', 'author' => 'Dr. William Harris', 'publisher' => 'National Archives Press', 'isbn' => '978-0-16-092545-5', 'type' => 'reference', 'subject' => 'History', 'grade_level' => '11-12', 'description' => 'Primary sources, letters, and documents from the American Revolution period.'],

            // Literature
            ['title' => 'Shakespeare: Complete Works Annotated', 'author' => 'Prof. Eleanor Hastings', 'publisher' => 'Penguin Classics', 'isbn' => '978-0-14-139710-9', 'type' => 'reference', 'subject' => 'Literature', 'grade_level' => 'Undergraduate', 'description' => 'All 37 plays and 154 sonnets with extensive scholarly annotations and historical context.'],
            ['title' => 'Modern Poetry: Voices of the 21st Century', 'author' => 'Dr. Aisha Brown', 'publisher' => 'HarperCollins', 'isbn' => '978-0-06-293572-8', 'type' => 'ebook', 'subject' => 'Literature', 'grade_level' => '11-12', 'description' => 'Anthology of contemporary poetry with critical analysis and discussion questions.'],

            // English
            ['title' => 'Grammar and Composition for Young Writers', 'author' => 'Lisa Martinez', 'publisher' => 'Scholastic', 'isbn' => '978-0-54-590812-7', 'type' => 'textbook', 'subject' => 'English', 'grade_level' => '3-5', 'description' => 'Age-appropriate grammar lessons and creative writing exercises for elementary students.'],
            ['title' => 'Academic Writing: A University Guide', 'author' => 'Prof. Thomas Anderson', 'publisher' => 'Routledge', 'isbn' => '978-1-13-854622-9', 'type' => 'textbook', 'subject' => 'English', 'grade_level' => 'Undergraduate', 'edition' => '2nd', 'description' => 'From thesis statements to research papers: a step-by-step academic writing handbook.'],

            // Geography
            ['title' => 'Physical Geography: Landscapes and Environments', 'author' => 'Dr. Nina Volkov', 'publisher' => 'Wiley', 'isbn' => '978-1-11-967851-2', 'type' => 'textbook', 'subject' => 'Geography', 'grade_level' => 'Undergraduate', 'description' => 'Earth systems, climate patterns, and geomorphology with GIS applications.'],

            // Economics
            ['title' => 'Principles of Microeconomics', 'author' => 'Prof. Samuel Okafor', 'publisher' => 'McGraw-Hill', 'isbn' => '978-0-07-802186-1', 'type' => 'textbook', 'subject' => 'Economics', 'grade_level' => 'Undergraduate', 'edition' => '6th', 'description' => 'Supply and demand, market structures, and game theory with real-world case studies.'],
            ['title' => 'Global Economics: Trade and Development', 'author' => 'Dr. Hannah Fischer', 'publisher' => 'Digital Academic', 'isbn' => '978-0-98-712345-6', 'type' => 'ebook', 'subject' => 'Economics', 'grade_level' => 'Graduate', 'description' => 'International trade theory, development economics, and globalisation trends.'],

            // Art
            ['title' => 'Art Through the Ages: A Global History', 'author' => 'Prof. Caroline Shaw', 'publisher' => 'Wadsworth', 'isbn' => '978-1-30-508478-1', 'type' => 'reference', 'subject' => 'Art', 'grade_level' => 'Undergraduate', 'edition' => '16th', 'description' => 'Comprehensive survey of art from prehistoric cave paintings to contemporary digital art.'],

            // Music
            ['title' => 'Music Theory and Practice', 'author' => 'Dr. Daniel Park', 'publisher' => 'Norton', 'isbn' => '978-0-39-361577-3', 'type' => 'textbook', 'subject' => 'Music', 'grade_level' => '9-10', 'description' => 'Fundamentals of rhythm, harmony, and composition for high school musicians.'],

            // Physical Education
            ['title' => 'Sports Science and Physical Fitness', 'author' => 'Coach Rebecca Adams', 'publisher' => 'Human Kinetics', 'isbn' => '978-1-49-254731-2', 'type' => 'textbook', 'subject' => 'Physical Education', 'grade_level' => '9-10', 'edition' => '3rd', 'description' => 'Exercise physiology, nutrition, and training principles for student athletes.'],

            // Additional resource
            ['title' => 'Early Childhood Learning Activities', 'author' => 'Maria Gonzalez', 'publisher' => 'Scholastic', 'isbn' => '978-0-54-590813-4', 'type' => 'textbook', 'subject' => 'Science', 'grade_level' => 'K-2', 'description' => 'Hands-on science experiments and discovery activities for kindergarten through second grade.'],
        ];

        foreach ($resources as $resource) {
            Resource::create(array_merge($resource, [
                'language'   => 'English',
                'created_by' => 1, // Admin user
            ]));
        }
    }
}
