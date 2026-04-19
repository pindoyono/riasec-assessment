<?php

namespace Tests\Unit;

use App\Models\Assessment;
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
}
