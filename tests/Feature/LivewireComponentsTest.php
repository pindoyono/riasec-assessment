<?php

namespace Tests\Feature;

use App\Livewire\AssessmentResult;
use App\Livewire\StudentLogin;
use App\Livewire\TakeAssessment;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\School;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireComponentsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RiasecCategorySeeder::class);
        $this->seed(\Database\Seeders\QuestionSeeder::class);
        $this->seed(\Database\Seeders\SmkMajorSeeder::class);
    }

    protected function createSchoolWithToken(): School
    {
        $school = School::create([
            'name' => 'Lokasi Test SMK',
            'type' => 'smk',
            'is_active' => true,
        ]);

        $school->generateToken(24);

        return $school;
    }

    protected function createStudentWithSchool(School $school): Student
    {
        return Student::create([
            'nisn' => '1234567890',
            'name' => 'Test Student',
            'gender' => 'L',
            'school_id' => $school->id,
        ]);
    }

    public function test_student_login_component_renders(): void
    {
        Livewire::test(StudentLogin::class)
            ->assertStatus(200)
            ->assertSee('Token Lokasi');
    }

    public function test_student_login_validates_token_length(): void
    {
        Livewire::test(StudentLogin::class)
            ->set('token', 'AB')  // Too short
            ->call('validateToken')
            ->assertHasErrors(['token' => 'min']);
    }

    public function test_student_login_shows_error_for_invalid_token(): void
    {
        Livewire::test(StudentLogin::class)
            ->set('token', 'INVALID1')
            ->call('validateToken')
            ->assertSet('error', 'Token tidak valid, sudah expired, atau tidak ditemukan.');
    }

    public function test_student_login_validates_token_and_shows_nisn_form(): void
    {
        $school = $this->createSchoolWithToken();

        Livewire::test(StudentLogin::class)
            ->set('token', $school->registration_token)
            ->call('validateToken')
            ->assertSet('tokenValidated', true)
            ->assertSee($school->name);
    }

    public function test_student_login_validates_nisn_against_school(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        Livewire::test(StudentLogin::class)
            ->set('token', $school->registration_token)
            ->call('validateToken')
            ->set('nisn', $student->nisn)
            ->call('login')
            ->assertRedirect();

        $this->assertDatabaseHas('assessments', [
            'student_id' => $student->id,
        ]);
    }

    public function test_student_login_shows_error_for_invalid_nisn(): void
    {
        $school = $this->createSchoolWithToken();

        Livewire::test(StudentLogin::class)
            ->set('token', $school->registration_token)
            ->call('validateToken')
            ->set('nisn', '9999999999')
            ->call('login')
            ->assertSet('error', 'NISN tidak ditemukan di lokasi test ini. Pastikan NISN benar dan Anda terdaftar di lokasi ini.');
    }

    public function test_student_login_redirects_to_result_if_completed(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        // Create completed assessment
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-COMPLETE1',
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

        Livewire::test(StudentLogin::class)
            ->set('token', $school->registration_token)
            ->call('validateToken')
            ->set('nisn', $student->nisn)
            ->call('login')
            ->assertRedirect(route('assessment.result', ['assessmentCode' => $assessment->assessment_code]));
    }

    public function test_back_to_token_resets_state(): void
    {
        $school = $this->createSchoolWithToken();

        Livewire::test(StudentLogin::class)
            ->set('token', $school->registration_token)
            ->call('validateToken')
            ->assertSet('tokenValidated', true)
            ->call('backToToken')
            ->assertSet('tokenValidated', false)
            ->assertSet('nisn', '')
            ->assertSet('error', '');
    }

    public function test_take_assessment_component_renders_for_pending_assessment(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-PENDING1',
            'status' => 'pending',
        ]);

        Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertStatus(200)
            ->assertSee($student->name);
    }

    public function test_take_assessment_redirects_if_already_completed(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-DONE1',
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

        Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertRedirect(route('assessment.result', ['assessmentCode' => $assessment->assessment_code]));
    }

    public function test_take_assessment_can_answer_question(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-ANSWER1',
            'status' => 'pending',
        ]);

        $question = Question::first();

        Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->call('answerQuestion', $question->id, 4)
            ->assertSet('currentIndex', 1);

        $this->assertDatabaseHas('assessment_answers', [
            'assessment_id' => $assessment->id,
            'question_id' => $question->id,
            'answer' => 4,
        ]);
    }

    public function test_take_assessment_ignores_invalid_answer_values(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-INVALID1',
            'status' => 'pending',
        ]);

        $question = Question::first();

        Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->call('answerQuestion', $question->id, 10)  // Invalid: > 5
            ->assertSet('currentIndex', 0);  // Should not advance

        $this->assertDatabaseMissing('assessment_answers', [
            'assessment_id' => $assessment->id,
            'question_id' => $question->id,
        ]);
    }

    public function test_take_assessment_can_navigate_questions(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-NAV1',
            'status' => 'pending',
        ]);

        Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertSet('currentIndex', 0)
            ->call('nextQuestion')
            ->assertSet('currentIndex', 1)
            ->call('nextQuestion')
            ->assertSet('currentIndex', 2)
            ->call('previousQuestion')
            ->assertSet('currentIndex', 1)
            ->call('goToQuestion', 5)
            ->assertSet('currentIndex', 5);
    }

    public function test_take_assessment_completes_when_all_answered(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-FULL1',
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        $questions = Question::all();
        $component = Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code]);

        // Answer all questions
        foreach ($questions as $index => $question) {
            $component->call('answerQuestion', $question->id, 4);
        }

        // Should redirect to result
        $component->assertRedirect(route('assessment.result', ['assessmentCode' => $assessment->assessment_code]));

        // Verify assessment is completed
        $assessment->refresh();
        $this->assertEquals('completed', $assessment->status);
        $this->assertNotNull($assessment->riasec_code);
    }

    public function test_assessment_result_component_renders_for_completed_assessment(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-RESULT1',
            'status' => 'completed',
            'completed_at' => now(),
            'score_r' => 85,
            'score_i' => 70,
            'score_a' => 60,
            'score_s' => 75,
            'score_e' => 80,
            'score_c' => 65,
            'riasec_code' => 'RES',
        ]);

        Livewire::test(AssessmentResult::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertStatus(200)
            ->assertSee($student->name)
            ->assertSee('RES');
    }

    public function test_assessment_result_redirects_if_not_completed(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-NOTDONE1',
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        Livewire::test(AssessmentResult::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertRedirect(route('assessment.take', ['assessmentCode' => $assessment->assessment_code]));
    }

    public function test_assessment_result_has_chart_data(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-CHART1',
            'status' => 'completed',
            'completed_at' => now(),
            'score_r' => 85,
            'score_i' => 70,
            'score_a' => 60,
            'score_s' => 75,
            'score_e' => 80,
            'score_c' => 65,
            'riasec_code' => 'RES',
        ]);

        $component = Livewire::test(AssessmentResult::class, ['assessmentCode' => $assessment->assessment_code]);

        $chartData = $component->get('chartData');
        $this->assertArrayHasKey('labels', $chartData);
        $this->assertArrayHasKey('data', $chartData);
        $this->assertCount(6, $chartData['labels']);
        $this->assertCount(6, $chartData['data']);
    }

    public function test_assessment_generates_recommendations(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-RECO1',
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        // Create answers for all questions
        $questions = Question::all();
        foreach ($questions as $question) {
            $assessment->answers()->create([
                'question_id' => $question->id,
                'answer' => 4,
                'answered_at' => now(),
            ]);
        }

        // Complete assessment
        $assessment->complete();

        // Check recommendations were generated
        $assessment->refresh();
        $this->assertTrue($assessment->recommendations->count() > 0);
        $this->assertTrue($assessment->recommendations->count() <= 10);
    }

    public function test_progress_percentage_calculates_correctly(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);
        $assessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-PROG1',
            'status' => 'pending',
        ]);

        // Create 30 answers (out of 60 questions)
        $questions = Question::take(30)->get();
        foreach ($questions as $question) {
            $assessment->answers()->create([
                'question_id' => $question->id,
                'answer' => 4,
                'answered_at' => now(),
            ]);
        }

        $component = Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code]);
        $this->assertEquals(50, $component->get('progressPercentage'));
    }
}
