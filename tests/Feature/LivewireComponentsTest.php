<?php

namespace Tests\Feature;

use App\Livewire\AssessmentResult;
use App\Livewire\StudentLogin;
use App\Livewire\TakeAssessment;
use App\Livewire\TakeForcedChoiceAssessment;
use App\Models\Assessment;
use App\Models\ForcedChoiceAssessmentAnswer;
use App\Models\ForcedChoiceQuestion;
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
        $this->seed(\Database\Seeders\ForcedChoiceQuestionSeeder::class);
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

    /** Create FC answers for all active questions (all choosing option A). */
    protected function createFcAnswersForAssessment(Assessment $assessment): void
    {
        $questions = ForcedChoiceQuestion::getForAssessment();

        foreach ($questions as $question) {
            ForcedChoiceAssessmentAnswer::create([
                'assessment_id'             => $assessment->id,
                'forced_choice_question_id' => $question->id,
                'selected_option'           => 'A',
                'selected_type'             => $question->option_a_type,
                'answered_at'               => now(),
            ]);
        }
    }

    public function test_student_login_component_renders(): void
    {
        Livewire::test(StudentLogin::class)
            ->assertStatus(200)
            ->assertSee('Masukkan NISN');
    }

    public function test_student_login_validates_nisn_format(): void
    {
        Livewire::test(StudentLogin::class)
            ->set('nisn', '123')
            ->call('checkNisn')
            ->assertHasErrors(['nisn' => 'digits']);
    }

    public function test_student_login_shows_error_for_invalid_token(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        Livewire::test(StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->set('token', 'INVALID1')
            ->call('login')
            ->assertSet('error', 'Token tidak valid, sudah expired, atau tidak ditemukan.');
    }

    public function test_student_login_validates_nisn_and_shows_token_form(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        Livewire::test(StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->assertSet('nisnValidated', true)
            ->assertSee('Token Lokasi');
    }

    public function test_student_login_creates_assessment_after_valid_nisn_and_token(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        Livewire::test(StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->set('token', $school->registration_token)
            ->call('login')
            ->assertRedirect();

        $this->assertDatabaseHas('assessments', [
            'student_id' => $student->id,
        ]);
    }

    public function test_student_login_shows_error_for_invalid_nisn(): void
    {
        Livewire::test(StudentLogin::class)
            ->set('nisn', '9999999999')
            ->call('checkNisn')
            ->assertRedirect(route('assessment.register', ['nisn' => '9999999999']));
    }

    public function test_student_login_redirects_to_result_if_completed(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        // Create completed assessment WITH forced choice answers
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
        $this->createFcAnswersForAssessment($assessment);

        Livewire::test(StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->assertRedirect(route('assessment.result', ['assessmentCode' => $assessment->assessment_code]));
    }

    public function test_student_login_reuses_existing_pending_assessment(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        $existingAssessment = Assessment::create([
            'student_id' => $student->id,
            'assessment_code' => 'ASM-EXISTING1',
            'status' => 'pending',
        ]);

        Livewire::test(StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->set('token', $school->registration_token)
            ->call('login')
            ->assertRedirect(route('assessment.take', ['assessmentCode' => $existingAssessment->assessment_code]));

        $this->assertEquals(
            1,
            Assessment::where('student_id', $student->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count()
        );
    }

    public function test_back_to_nisn_resets_state(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        Livewire::test(StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->assertSet('nisnValidated', true)
            ->set('token', 'ABCDEF12')
            ->call('backToNisn')
            ->assertSet('nisnValidated', false)
            ->assertSet('token', '')
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
        $this->createFcAnswersForAssessment($assessment);

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

        // Should redirect to forced choice (next step after Likert)
        $component->assertRedirect(route('assessment.forced-choice.take', ['assessmentCode' => $assessment->assessment_code]));

        // Verify Likert assessment is completed
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
        $this->createFcAnswersForAssessment($assessment);

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
        $this->createFcAnswersForAssessment($assessment);

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

    // ────────────────────────────────────────────────────────────────────────
    // Forced Choice Tests
    // ────────────────────────────────────────────────────────────────────────

    public function test_student_login_redirects_to_fc_when_likert_done_but_no_fc(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        Assessment::create([
            'student_id'     => $student->id,
            'assessment_code' => 'ASM-NO-FC',
            'status'         => 'completed',
            'completed_at'   => now(),
            'score_r' => 80, 'score_i' => 80, 'score_a' => 80,
            'score_s' => 80, 'score_e' => 80, 'score_c' => 80,
            'riasec_code' => 'RIA',
        ]);

        // No FC answers — should redirect to forced-choice step
        Livewire::test(StudentLogin::class)
            ->set('nisn', $student->nisn)
            ->call('checkNisn')
            ->assertRedirect(route('assessment.forced-choice.take', ['assessmentCode' => 'ASM-NO-FC']));
    }

    public function test_take_assessment_redirects_to_fc_when_completed_no_fc(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        $assessment = Assessment::create([
            'student_id'     => $student->id,
            'assessment_code' => 'ASM-FC-REDIR',
            'status'         => 'completed',
            'completed_at'   => now(),
            'score_r' => 80, 'score_i' => 80, 'score_a' => 80,
            'score_s' => 80, 'score_e' => 80, 'score_c' => 80,
            'riasec_code' => 'RIA',
        ]);
        // No FC answers

        Livewire::test(TakeAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertRedirect(route('assessment.forced-choice.take', ['assessmentCode' => $assessment->assessment_code]));
    }

    public function test_assessment_result_redirects_to_fc_if_fc_not_done(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        $assessment = Assessment::create([
            'student_id'     => $student->id,
            'assessment_code' => 'ASM-RESULT-FC',
            'status'         => 'completed',
            'completed_at'   => now(),
            'score_r' => 85, 'score_i' => 70, 'score_a' => 60,
            'score_s' => 75, 'score_e' => 80, 'score_c' => 65,
            'riasec_code' => 'RES',
        ]);
        // No FC answers — result page must redirect to FC

        Livewire::test(AssessmentResult::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertRedirect(route('assessment.forced-choice.take', ['assessmentCode' => $assessment->assessment_code]));
    }

    public function test_take_forced_choice_component_renders(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        $assessment = Assessment::create([
            'student_id'     => $student->id,
            'assessment_code' => 'ASM-FC-RENDER',
            'status'         => 'completed',
            'completed_at'   => now(),
            'score_r' => 80, 'score_i' => 80, 'score_a' => 80,
            'score_s' => 80, 'score_e' => 80, 'score_c' => 80,
            'riasec_code' => 'RIA',
        ]);

        Livewire::test(TakeForcedChoiceAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->assertStatus(200)
            ->assertSee($student->name);
    }

    public function test_take_forced_choice_can_answer_question(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        $assessment = Assessment::create([
            'student_id'     => $student->id,
            'assessment_code' => 'ASM-FC-ANS',
            'status'         => 'completed',
            'completed_at'   => now(),
            'score_r' => 80, 'score_i' => 80, 'score_a' => 80,
            'score_s' => 80, 'score_e' => 80, 'score_c' => 80,
            'riasec_code' => 'RIA',
        ]);

        $question = ForcedChoiceQuestion::first();

        Livewire::test(TakeForcedChoiceAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->call('answerQuestion', $question->id, 'A')
            ->assertSet('currentIndex', 1);

        $this->assertDatabaseHas('forced_choice_assessment_answers', [
            'assessment_id'             => $assessment->id,
            'forced_choice_question_id' => $question->id,
            'selected_option'           => 'A',
        ]);
    }

    public function test_take_forced_choice_ignores_invalid_option(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        $assessment = Assessment::create([
            'student_id'     => $student->id,
            'assessment_code' => 'ASM-FC-INVALID',
            'status'         => 'completed',
            'completed_at'   => now(),
            'score_r' => 80, 'score_i' => 80, 'score_a' => 80,
            'score_s' => 80, 'score_e' => 80, 'score_c' => 80,
            'riasec_code' => 'RIA',
        ]);

        $question = ForcedChoiceQuestion::first();

        Livewire::test(TakeForcedChoiceAssessment::class, ['assessmentCode' => $assessment->assessment_code])
            ->call('answerQuestion', $question->id, 'X')  // invalid
            ->assertSet('currentIndex', 0);              // should not advance

        $this->assertDatabaseMissing('forced_choice_assessment_answers', [
            'assessment_id'             => $assessment->id,
            'forced_choice_question_id' => $question->id,
        ]);
    }

    public function test_take_forced_choice_saves_combined_scores_on_complete(): void
    {
        $school = $this->createSchoolWithToken();
        $student = $this->createStudentWithSchool($school);

        $assessment = Assessment::create([
            'student_id'     => $student->id,
            'assessment_code' => 'ASM-FC-COMBINED',
            'status'         => 'completed',
            'completed_at'   => now(),
            'score_r' => 60.0, 'score_i' => 70.0, 'score_a' => 50.0,
            'score_s' => 80.0, 'score_e' => 40.0, 'score_c' => 90.0,
            'riasec_code'    => 'CSI',
        ]);

        $component = Livewire::test(
            TakeForcedChoiceAssessment::class,
            ['assessmentCode' => $assessment->assessment_code]
        );

        // Answer all FC questions with option A
        foreach (ForcedChoiceQuestion::getForAssessment() as $question) {
            $component->call('answerQuestion', $question->id, 'A');
        }

        // Complete FC — triggers combined score calculation
        $component->call('completeAssessment')
            ->assertRedirect(route('assessment.result', ['assessmentCode' => $assessment->assessment_code]));

        $assessment->refresh();

        // Scores must be within valid range
        foreach (['score_r', 'score_i', 'score_a', 'score_s', 'score_e', 'score_c'] as $col) {
            $this->assertGreaterThanOrEqual(0, $assessment->$col);
            $this->assertLessThanOrEqual(100, $assessment->$col);
        }

        // RIASEC code must be 3 letters
        $this->assertEquals(3, strlen($assessment->riasec_code));
        $this->assertMatchesRegularExpression('/^[RIASEC]{3}$/', $assessment->riasec_code);
    }
}
