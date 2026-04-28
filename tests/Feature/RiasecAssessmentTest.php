<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\RiasecCategory;
use App\Models\School;
use App\Models\SmkMajor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiasecAssessmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed required data
        $this->seed(\Database\Seeders\RiasecCategorySeeder::class);
        $this->seed(\Database\Seeders\QuestionSeeder::class);
        $this->seed(\Database\Seeders\SmkMajorSeeder::class);
    }

    public function test_assessment_login_page_loads(): void
    {
        $response = $this->get('/assessment');

        $response->assertStatus(200);
        $response->assertSee('RIASEC Assessment');
        $response->assertSee('Masukkan NISN');
    }

    public function test_riasec_categories_are_seeded(): void
    {
        $this->assertEquals(6, RiasecCategory::count());

        $codes = RiasecCategory::orderBy('order')->pluck('code')->toArray();
        $this->assertEquals(['R', 'I', 'A', 'S', 'E', 'C'], $codes);
    }

    public function test_questions_are_seeded(): void
    {
        $this->assertEquals(60, Question::count());

        // Each category should have 10 questions
        foreach (RiasecCategory::all() as $category) {
            $count = Question::where('riasec_category_id', $category->id)->count();
            $this->assertEquals(10, $count, "Category {$category->code} should have 10 questions");
        }
    }

    public function test_smk_majors_are_seeded(): void
    {
        // Updated to match Kurikulum Merdeka 2024 spectrum (84 majors)
        $this->assertEquals(84, SmkMajor::count());

        // All majors should have RIASEC profiles
        $majorsWithoutProfile = SmkMajor::whereNull('riasec_profile')->count();
        $this->assertEquals(0, $majorsWithoutProfile);
    }

    public function test_student_can_be_created_with_school(): void
    {
        $school = School::create([
            'name' => 'SMP Negeri 1 Test',
            'npsn' => '12345678',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'nisn' => '1234567890',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'TESTTOKEN',
        ]);

        $this->assertDatabaseHas('students', [
            'name' => 'Test Student',
            'registration_token' => 'TESTTOKEN',
        ]);

        $this->assertEquals($school->id, $student->school_id);
    }

    public function test_assessment_can_be_created_for_student(): void
    {
        $school = School::create([
            'name' => 'SMP Test',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'TESTTOKEN',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-' . strtoupper(uniqid()),
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('assessments', [
            'student_id' => $student->id,
            'status' => 'pending',
        ]);
    }

    public function test_assessment_score_calculation(): void
    {
        $school = School::create([
            'name' => 'SMP Test',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'TESTTOKEN',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-' . strtoupper(uniqid()),
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        // Create answers for all 60 questions
        $questions = Question::all();
        foreach ($questions as $question) {
            $assessment->answers()->create([
                'question_id' => $question->id,
                'answer' => 4, // Score of 4 for all questions
                'answered_at' => now(),
            ]);
        }

        // Complete the assessment (this should calculate scores)
        $assessment->complete();

        $assessment->refresh();

        $this->assertEquals('completed', $assessment->status);
        $this->assertNotNull($assessment->completed_at);
        $this->assertNotNull($assessment->riasec_code);

        // All scores should be 80% (4*10 = 40, max = 50, 40/50 = 80%)
        $this->assertEquals(80, $assessment->score_r);
        $this->assertEquals(80, $assessment->score_i);
        $this->assertEquals(80, $assessment->score_a);
        $this->assertEquals(80, $assessment->score_s);
        $this->assertEquals(80, $assessment->score_e);
        $this->assertEquals(80, $assessment->score_c);
    }

    public function test_invalid_token_shows_error(): void
    {
        $response = $this->get('/assessment');
        $response->assertStatus(200);

        $school = School::create([
            'name' => 'Lokasi Test SMK',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'nisn' => '1234567890',
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
        ]);

        \Livewire\Livewire::test(\App\Livewire\StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->set('token', 'NOTFOUND')
            ->call('login')
            ->assertSet('error', 'Token tidak valid, sudah expired, atau tidak ditemukan.');
    }

    public function test_valid_token_and_nisn_creates_assessment(): void
    {
        $school = School::create([
            'name' => 'Lokasi Test SMK',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $school->generateToken(24);

        $student = Student::create([
            'nisn' => '1234567890',
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
        ]);

        \Livewire\Livewire::test(\App\Livewire\StudentLogin::class)
            ->set('nisn', '1234567890')
            ->call('checkNisn')
            ->assertSet('nisnValidated', true)
            ->set('token', $school->registration_token)
            ->call('login')
            ->assertRedirect();

        $this->assertDatabaseHas('assessments', [
            'student_id' => $student->id,
            'status' => 'pending', // Login creates pending assessment
        ]);
    }

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_model_relationships(): void
    {
        $school = School::create([
            'name' => 'SMP Test',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
            'registration_token' => 'TESTTOKEN',
        ]);

        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-TEST',
            'status' => 'pending',
        ]);

        // Test relationships
        $this->assertEquals('SMP Test', $student->school->name);
        $this->assertEquals('Test Student', $assessment->student->name);
        $this->assertTrue($school->students->contains($student));
        $this->assertTrue($student->assessments->contains($assessment));
    }

    public function test_riasec_category_questions_relationship(): void
    {
        $category = RiasecCategory::where('code', 'R')->first();

        $this->assertEquals(10, $category->questions->count());
    }
}
