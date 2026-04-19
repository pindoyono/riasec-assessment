<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Question;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $codes = ['R', 'I', 'A', 'S', 'E', 'C'];
        $categoryMap = [
            'R' => 1, 'I' => 2, 'A' => 3,
            'S' => 4, 'E' => 5, 'C' => 6,
        ];

        // All C(6,3) = 20 combinations
        $combinations = [];
        for ($i = 0; $i < 6; $i++) {
            for ($j = $i + 1; $j < 6; $j++) {
                for ($k = $j + 1; $k < 6; $k++) {
                    $combinations[] = [$codes[$i], $codes[$j], $codes[$k]];
                }
            }
        }

        // Get questions grouped by category_id
        $questionsByCategory = Question::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->groupBy('riasec_category_id');

        $schoolId = School::first()?->id;

        $names = [
            'RIA' => 'Ahmad Rizki',
            'RIS' => 'Budi Santoso',
            'RIE' => 'Citra Dewi',
            'RIC' => 'Dani Pratama',
            'RAS' => 'Eka Putri',
            'RAE' => 'Fajar Nugroho',
            'RAC' => 'Gita Sari',
            'RSE' => 'Hadi Wijaya',
            'RSC' => 'Indah Permata',
            'REC' => 'Joko Susanto',
            'IAS' => 'Kartika Sari',
            'IAE' => 'Lukman Hakim',
            'IAC' => 'Maya Anggraini',
            'ISE' => 'Nanda Putra',
            'ISC' => 'Olivia Rahman',
            'IEC' => 'Putra Mandiri',
            'ASE' => 'Qori Amalia',
            'ASC' => 'Rama Dhani',
            'AEC' => 'Sinta Maharani',
            'SEC' => 'Teguh Prabowo',
        ];

        foreach ($combinations as $combo) {
            $riasecCode = implode('', $combo);
            $studentName = $names[$riasecCode] ?? 'Siswa ' . $riasecCode;

            // Create student
            $student = Student::create([
                'nisn' => '00' . rand(10000000, 99999999),
                'name' => $studentName,
                'gender' => rand(0, 1) ? 'L' : 'P',
                'birth_place' => 'Tarakan',
                'birth_date' => now()->subYears(rand(15, 17))->subDays(rand(0, 365)),
                'school_id' => $schoolId,
                'class' => 'IX-' . rand(1, 5),
                'registration_token' => strtoupper(Str::random(8)),
                'is_active' => true,
            ]);

            // Create assessment
            $assessment = Assessment::create([
                'student_id' => $student->id,
                'assessment_code' => 'ASM' . now()->format('Ymd') . strtoupper(Str::random(4)),
                'status' => 'in_progress',
                'started_at' => now()->subMinutes(rand(10, 30)),
            ]);

            // Determine answer values per category to produce desired top-3
            // Top 3 codes get high answers (4-5), remaining get low answers (1-2)
            $answerStrategy = [];
            foreach ($codes as $idx => $code) {
                if ($code === $combo[0]) {
                    $answerStrategy[$categoryMap[$code]] = [5, 5, 5, 5, 5, 4, 5, 4, 5, 5]; // ~96%
                } elseif ($code === $combo[1]) {
                    $answerStrategy[$categoryMap[$code]] = [4, 5, 4, 5, 4, 4, 5, 4, 4, 5]; // ~88%
                } elseif ($code === $combo[2]) {
                    $answerStrategy[$categoryMap[$code]] = [4, 4, 4, 3, 4, 4, 3, 4, 4, 4]; // ~76%
                } else {
                    // Low scores for non-top categories, each slightly different
                    $position = array_search($code, array_diff($codes, $combo));
                    $lowSets = [
                        [2, 2, 1, 2, 1, 2, 2, 1, 2, 1], // ~32%
                        [1, 2, 2, 1, 2, 1, 1, 2, 1, 2], // ~30%
                        [1, 1, 2, 1, 1, 2, 1, 1, 2, 1], // ~26%
                    ];
                    $lowIdx = min((int) array_search($code, array_values(array_diff($codes, $combo))), 2);
                    $answerStrategy[$categoryMap[$code]] = $lowSets[$lowIdx];
                }
            }

            // Create answers
            foreach ($questionsByCategory as $catId => $questions) {
                $answers = $answerStrategy[$catId] ?? [3, 3, 3, 3, 3, 3, 3, 3, 3, 3];
                foreach ($questions as $qIdx => $question) {
                    AssessmentAnswer::create([
                        'assessment_id' => $assessment->id,
                        'question_id' => $question->id,
                        'answer' => $answers[$qIdx] ?? 3,
                        'answered_at' => now()->subMinutes(rand(1, 20)),
                    ]);
                }
            }

            // Complete the assessment (calculates scores, riasec_code, recommendations)
            $assessment->complete();

            $this->command->info("Created: {$studentName} → {$assessment->riasec_code}");
        }

        $this->command->info('');
        $this->command->info('Done! Created ' . count($combinations) . ' dummy assessments covering all 20 RIASEC combinations.');
    }
}
