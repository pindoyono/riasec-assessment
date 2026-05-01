<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\ForcedChoiceAssessmentAnswer;
use App\Models\ForcedChoiceQuestion;
use App\Models\Question;
use App\Models\RiasecCategory;
use App\Models\School;
use App\Models\SmkMajor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RiasecCategorySeeder::class);
        $this->seed(\Database\Seeders\QuestionSeeder::class);
        $this->seed(\Database\Seeders\SmkMajorSeeder::class);
        $this->seed(\Database\Seeders\ForcedChoiceQuestionSeeder::class);
    }

    public function test_student_can_be_found_by_token(): void
    {
        $school = School::create([
            'name' => 'Test School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'FINDME123',
        ]);

        $found = Student::findByToken('FINDME123');
        $this->assertNotNull($found);
        $this->assertEquals($student->id, $found->id);

        $notFound = Student::findByToken('NOTEXIST1');
        $this->assertNull($notFound);
    }

    public function test_student_can_check_completed_assessment(): void
    {
        $school = School::create([
            'name' => 'Test School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'COMPLETE1',
        ]);

        // No assessment yet
        $this->assertFalse($student->hasCompletedAssessment());

        // Create completed assessment
        Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-DONE',
            'status' => 'completed',
            'completed_at' => now(),
            'score_r' => 80,
            'score_i' => 80,
            'score_a' => 80,
            'score_s' => 80,
            'score_e' => 80,
            'score_c' => 80,
            'riasec_code' => 'RIA',
        ]);

        $student->refresh();
        $this->assertTrue($student->hasCompletedAssessment());
    }

    public function test_assessment_generates_unique_code(): void
    {
        $school = School::create([
            'name' => 'Test School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'UNIQUE123',
        ]);

        $assessment1 = Assessment::create([
            'student_id' => $student->id,
            'status' => 'pending',
        ]);

        $assessment2 = Assessment::create([
            'student_id' => $student->id,
            'status' => 'pending',
        ]);

        $this->assertNotNull($assessment1->assessment_code);
        $this->assertNotNull($assessment2->assessment_code);
        $this->assertNotEquals($assessment1->assessment_code, $assessment2->assessment_code);
        $this->assertStringStartsWith('ASM', $assessment1->assessment_code);
    }

    public function test_assessment_can_start(): void
    {
        $school = School::create([
            'name' => 'Test School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'START123',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'status' => 'pending',
        ]);

        $assessment->start('192.168.1.1', 'Test Browser');

        $assessment->refresh();
        $this->assertEquals('in_progress', $assessment->status);
        $this->assertNotNull($assessment->started_at);
        $this->assertEquals('192.168.1.1', $assessment->ip_address);
        $this->assertEquals('Test Browser', $assessment->user_agent);
    }

    public function test_assessment_calculates_riasec_code_correctly(): void
    {
        $school = School::create([
            'name' => 'Test School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'RIASEC123',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'score_r' => 90,
            'score_i' => 60,
            'score_a' => 85,
            'score_s' => 70,
            'score_e' => 80,
            'score_c' => 50,
        ]);

        $assessment->generateRiasecCode();
        $assessment->refresh();

        // R=90, A=85, E=80 are top 3
        $this->assertEquals('RAE', $assessment->riasec_code);
    }

    public function test_assessment_formats_duration_correctly(): void
    {
        $school = School::create([
            'name' => 'Test School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'DURATION1',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'status' => 'completed',
            'duration_seconds' => 725, // 12 minutes 5 seconds
        ]);

        $this->assertEquals('12 menit 5 detik', $assessment->formatted_duration);
    }

    public function test_riasec_category_has_correct_attributes(): void
    {
        $category = RiasecCategory::where('code', 'R')->first();

        $this->assertEquals('Realistic', $category->name);
        $this->assertNotNull($category->description);
        $this->assertNotNull($category->color);
        $this->assertEquals(10, $category->questions->count());
    }

    public function test_question_belongs_to_category(): void
    {
        $question = Question::first();

        $this->assertNotNull($question->riasecCategory);
        $this->assertInstanceOf(RiasecCategory::class, $question->riasecCategory);
    }

    public function test_smk_major_calculates_match_score(): void
    {
        $major = SmkMajor::first();

        $scores = [
            'score_r' => 90,
            'score_i' => 80,
            'score_a' => 70,
            'score_s' => 60,
            'score_e' => 50,
            'score_c' => 40,
        ];

        $matchScore = $major->calculateMatchScore($scores);

        $this->assertGreaterThan(0, $matchScore);
        $this->assertLessThanOrEqual(100, $matchScore);
    }

    public function test_smk_major_finds_by_riasec_code(): void
    {
        $majors = SmkMajor::findByRiasecCode('RIA');

        $this->assertGreaterThan(0, $majors->count());

        // All returned majors should have at least one matching RIASEC code
        foreach ($majors as $major) {
            $matchingCodes = array_intersect($major->riasec_profile, ['R', 'I', 'A']);
            $this->assertGreaterThan(0, count($matchingCodes));
        }
    }

    public function test_smk_major_scope_active_works(): void
    {
        // Deactivate one major
        $major = SmkMajor::first();
        $major->update(['is_active' => false]);

        $activeMajors = SmkMajor::active()->get();

        // Should not include the deactivated major
        $this->assertFalse($activeMajors->contains('id', $major->id));
    }

    public function test_school_can_have_multiple_students(): void
    {
        $school = School::create([
            'name' => 'Multi Student School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        Student::create([
            'name' => 'Student 1',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'TOKEN001',
        ]);

        Student::create([
            'name' => 'Student 2',
            'gender' => 'P',
            'school_id' => $school->id,
            'registration_token' => 'TOKEN002',
        ]);

        $this->assertEquals(2, $school->students->count());
    }

    public function test_school_scope_active_works(): void
    {
        $activeSchool = School::create([
            'name' => 'Active School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $inactiveSchool = School::create([
            'name' => 'Inactive School',
            'type' => 'smk',
            'is_active' => false,
        ]);

        $activeSchools = School::active()->get();

        $this->assertTrue($activeSchools->contains('id', $activeSchool->id));
        $this->assertFalse($activeSchools->contains('id', $inactiveSchool->id));
    }

    public function test_assessment_riasec_scores_attribute_returns_array(): void
    {
        $school = School::create([
            'name' => 'Test School',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'SCORES123',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'status' => 'completed',
            'score_r' => 90,
            'score_i' => 80,
            'score_a' => 70,
            'score_s' => 60,
            'score_e' => 50,
            'score_c' => 40,
            'riasec_code' => 'RIA',
        ]);

        $scores = $assessment->riasec_scores;

        $this->assertIsArray($scores);
        $this->assertEquals(90, $scores['R']);
        $this->assertEquals(80, $scores['I']);
        $this->assertEquals(70, $scores['A']);
        $this->assertEquals(60, $scores['S']);
        $this->assertEquals(50, $scores['E']);
        $this->assertEquals(40, $scores['C']);
    }

    // ────────────────────────────────────────────────────────────────────────
    // Forced Choice Model Tests
    // ────────────────────────────────────────────────────────────────────────

    public function test_fc_calculate_scores_returns_type_counts(): void
    {
        $school = School::create(['name' => 'FC School', 'type' => 'smk', 'is_active' => true]);
        $student = Student::create(['name' => 'FC Student', 'gender' => 'L', 'school_id' => $school->id]);
        $assessment = Assessment::create(['student_id' => $student->id, 'status' => 'completed']);

        $questions = ForcedChoiceQuestion::getForAssessment();
        $this->assertGreaterThan(0, $questions->count(), '30 FC questions must be seeded');

        // Answer all questions with option A
        foreach ($questions as $question) {
            ForcedChoiceAssessmentAnswer::create([
                'assessment_id'             => $assessment->id,
                'forced_choice_question_id' => $question->id,
                'selected_option'           => 'A',
                'selected_type'             => $question->option_a_type,
                'answered_at'               => now(),
            ]);
        }

        $scores = ForcedChoiceAssessmentAnswer::calculateScores($assessment->id);

        $this->assertArrayHasKey('R', $scores);
        $this->assertArrayHasKey('I', $scores);
        $this->assertArrayHasKey('A', $scores);
        $this->assertArrayHasKey('S', $scores);
        $this->assertArrayHasKey('E', $scores);
        $this->assertArrayHasKey('C', $scores);

        // Sum of all type counts must equal total number of answers
        $this->assertEquals($questions->count(), array_sum($scores));
    }

    public function test_fc_normalized_scores_sum_to_100(): void
    {
        $school = School::create(['name' => 'FC School 2', 'type' => 'smk', 'is_active' => true]);
        $student = Student::create(['name' => 'FC Student 2', 'gender' => 'L', 'school_id' => $school->id]);
        $assessment = Assessment::create(['student_id' => $student->id, 'status' => 'completed']);

        foreach (ForcedChoiceQuestion::getForAssessment() as $question) {
            ForcedChoiceAssessmentAnswer::create([
                'assessment_id'             => $assessment->id,
                'forced_choice_question_id' => $question->id,
                'selected_option'           => 'B',
                'selected_type'             => $question->option_b_type,
                'answered_at'               => now(),
            ]);
        }

        $normalized = ForcedChoiceAssessmentAnswer::calculateNormalizedScores($assessment->id);

        // Each value should be 0-100
        foreach ($normalized as $type => $value) {
            $this->assertGreaterThanOrEqual(0, $value);
            $this->assertLessThanOrEqual(100, $value);
        }

        // Sum should be ~100 (allow rounding tolerance)
        $this->assertEqualsWithDelta(100.0, array_sum($normalized), 1.0);
    }

    public function test_fc_normalized_scores_return_zeros_when_no_answers(): void
    {
        $school = School::create(['name' => 'FC School 3', 'type' => 'smk', 'is_active' => true]);
        $student = Student::create(['name' => 'FC Student 3', 'gender' => 'L', 'school_id' => $school->id]);
        $assessment = Assessment::create(['student_id' => $student->id, 'status' => 'completed']);

        $normalized = ForcedChoiceAssessmentAnswer::calculateNormalizedScores($assessment->id);

        foreach ($normalized as $value) {
            $this->assertEquals(0, $value);
        }
    }

    public function test_fc_combine_scores_weighted_average(): void
    {
        $likert = ['R' => 80.0, 'I' => 60.0, 'A' => 40.0, 'S' => 70.0, 'E' => 50.0, 'C' => 90.0];
        $fc     = ['R' => 30.0, 'I' => 20.0, 'A' => 10.0, 'S' => 15.0, 'E' => 5.0,  'C' => 20.0];

        $combined = ForcedChoiceAssessmentAnswer::combineScores($likert, $fc);

        // R = 80*0.6 + 30*0.4 = 48 + 12 = 60
        $this->assertEqualsWithDelta(60.0, $combined['R'], 0.1);
        // C = 90*0.6 + 20*0.4 = 54 + 8 = 62
        $this->assertEqualsWithDelta(62.0, $combined['C'], 0.1);

        // All values should be within 0-100
        foreach ($combined as $value) {
            $this->assertGreaterThanOrEqual(0, $value);
            $this->assertLessThanOrEqual(100, $value);
        }
    }

    public function test_fc_get_riasec_code_returns_top_3(): void
    {
        $scores = ['R' => 10, 'I' => 5, 'A' => 8, 'S' => 3, 'E' => 7, 'C' => 2];

        $code = ForcedChoiceAssessmentAnswer::getRiasecCode($scores);

        $this->assertEquals(3, strlen($code));
        // Top 3 are R=10, A=8, E=7
        $this->assertEquals('RAE', $code);
    }
}
