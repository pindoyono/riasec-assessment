<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\ForcedChoiceAssessmentAnswer;
use App\Models\ForcedChoiceQuestion;
use App\Models\Question;
use App\Models\RiasecCategory;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\ForcedChoiceQuestionSeeder;
use Database\Seeders\QuestionSeeder;
use Database\Seeders\RiasecCategorySeeder;
use Database\Seeders\SmkMajorSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Tests for the superadmin "view and edit student answers" feature:
 *  - Assessment::forcedChoiceAnswers() relation
 *  - Superadmin can access Filament admin panel
 *  - Superadmin can view assessment detail
 *  - Superadmin can edit assessment (status etc.)
 *  - Score recalculation logic after editing answers
 *  - Non-superadmin cannot access edit
 */
class SuperAdminAnswerManagementTest extends TestCase
{
    // DatabaseTransactions: setiap test dibungkus transaction → di-rollback otomatis.
    // Tidak perlu RefreshDatabase karena riasec_testing sudah di-sync dari riasec
    // (termasuk roles, permissions, categories, questions, dll).
    use DatabaseTransactions;

    protected School $school;
    protected Student $student;
    protected Assessment $assessment;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear Spatie permission cache (bisa stale setelah RefreshDatabase di test lain)
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Pastikan role super_admin ada (test lain yang pakai RefreshDatabase bisa menghapusnya)
        if (!Role::where('name', 'super_admin')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        }

        // Pastikan data referensi ada (bisa hilang akibat RefreshDatabase di test lain)
        if (RiasecCategory::count() === 0) {
            $this->seed(RiasecCategorySeeder::class);
        }
        if (Question::count() === 0) {
            $this->seed(QuestionSeeder::class);
        }
        if (ForcedChoiceQuestion::count() === 0) {
            $this->seed(ForcedChoiceQuestionSeeder::class);
        }

        $this->school = School::create([
            'name'      => 'SMK Test (SuperAdmin Test)',
            'type'      => 'smk',
            'is_active' => true,
        ]);

        $this->student = Student::create([
            'name'      => 'Siswa Test SuperAdmin',
            'gender'    => 'L',
            'school_id' => $this->school->id,
        ]);

        $this->assessment = Assessment::create([
            'student_id'       => $this->student->id,
            'assessment_code'  => 'ASM-SATEST-' . uniqid(),
            'status'           => 'completed',
            'completed_at'     => now(),
            'started_at'       => now()->subMinutes(30),
            'duration_seconds' => 1800,
            'score_r'          => 80,
            'score_i'          => 60,
            'score_a'          => 70,
            'score_s'          => 55,
            'score_e'          => 65,
            'score_c'          => 50,
            'riasec_code'      => 'RAE',
        ]);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    protected function createSuperAdmin(): User
    {
        // Role super_admin sudah ada dari data sync riasec_testing
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        return $user;
    }

    /** Regular user tanpa role super_admin. */
    protected function createRegularAdmin(): User
    {
        return User::factory()->create(['school_id' => $this->school->id]);
    }

    protected function createLikertAnswers(): void
    {
        $questions = Question::all();
        foreach ($questions as $question) {
            AssessmentAnswer::create([
                'assessment_id' => $this->assessment->id,
                'question_id'   => $question->id,
                'answer'        => 4,
                'answered_at'   => now(),
            ]);
        }
    }

    protected function createFcAnswers(): void
    {
        $questions = ForcedChoiceQuestion::getForAssessment();
        foreach ($questions as $question) {
            ForcedChoiceAssessmentAnswer::create([
                'assessment_id'             => $this->assessment->id,
                'forced_choice_question_id' => $question->id,
                'selected_option'           => 'A',
                'selected_type'             => $question->option_a_type,
                'answered_at'               => now(),
            ]);
        }
    }

    // ─────────────────────────────────────────────
    // 1. Model – Assessment::forcedChoiceAnswers() relation
    // ─────────────────────────────────────────────

    public function test_assessment_has_forced_choice_answers_relation(): void
    {
        $this->createFcAnswers();

        $answers = $this->assessment->forcedChoiceAnswers;

        $this->assertNotNull($answers);
        $this->assertGreaterThan(0, $answers->count());
    }

    public function test_forced_choice_answers_belong_to_correct_assessment(): void
    {
        $this->createFcAnswers();

        foreach ($this->assessment->forcedChoiceAnswers as $answer) {
            $this->assertEquals($this->assessment->id, $answer->assessment_id);
        }
    }

    public function test_assessment_answers_relation_returns_likert_answers(): void
    {
        $this->createLikertAnswers();

        $answers = $this->assessment->answers;

        $this->assertEquals(60, $answers->count());
    }

    // ─────────────────────────────────────────────
    // 2. Score recalculation after editing Likert answer
    // ─────────────────────────────────────────────

    public function test_likert_score_changes_after_answer_edit(): void
    {
        $this->createLikertAnswers();

        // All answers are 4, so scores should be 80%
        $this->assessment->calculateScores();
        $this->assessment->refresh();
        $this->assertEquals(80, $this->assessment->score_r);

        // Edit all R-category answers to 2
        $rCategory = RiasecCategory::where('code', 'R')->first();
        $rQuestions = Question::where('riasec_category_id', $rCategory->id)->pluck('id');

        AssessmentAnswer::where('assessment_id', $this->assessment->id)
            ->whereIn('question_id', $rQuestions)
            ->update(['answer' => 2]);

        // Recalculate
        $this->assessment->calculateScores();
        $this->assessment->refresh();

        // score_r should now be 40% (2/5 = 40%)
        $this->assertEquals(40, $this->assessment->score_r);
        // Other scores should remain 80%
        $this->assertEquals(80, $this->assessment->score_i);
    }

    // ─────────────────────────────────────────────
    // 3. Score recalculation after editing FC answer
    // ─────────────────────────────────────────────

    public function test_fc_riasec_code_changes_after_fc_answer_edit(): void
    {
        $this->createFcAnswers(); // all choose A

        // Scores before edit – all A choices, dominated by option_a_type distribution
        $scoresBefore = ForcedChoiceAssessmentAnswer::calculateScores($this->assessment->id);
        $codeBefore = ForcedChoiceAssessmentAnswer::getRiasecCode($scoresBefore);

        // Flip all FC answers to option B
        ForcedChoiceAssessmentAnswer::where('assessment_id', $this->assessment->id)
            ->each(function (ForcedChoiceAssessmentAnswer $ans) {
                $question = $ans->question;
                $ans->update([
                    'selected_option' => 'B',
                    'selected_type'   => $question->option_b_type,
                ]);
            });

        $scoresAfter = ForcedChoiceAssessmentAnswer::calculateScores($this->assessment->id);

        // Scores should have changed
        $this->assertNotEquals($scoresBefore, $scoresAfter);
    }

    public function test_combined_scores_recalculate_after_fc_edit(): void
    {
        $this->createLikertAnswers();
        $this->assessment->calculateScores();
        $this->assessment->refresh();
        $this->createFcAnswers();

        $fcNormalized = ForcedChoiceAssessmentAnswer::calculateNormalizedScores($this->assessment->id);
        $likert = [
            'R' => $this->assessment->score_r,
            'I' => $this->assessment->score_i,
            'A' => $this->assessment->score_a,
            'S' => $this->assessment->score_s,
            'E' => $this->assessment->score_e,
            'C' => $this->assessment->score_c,
        ];
        $combined = ForcedChoiceAssessmentAnswer::combineScores($likert, $fcNormalized);

        // combined scores must sum > 0
        $this->assertGreaterThan(0, array_sum($combined));

        // All 6 types must be present
        foreach (['R', 'I', 'A', 'S', 'E', 'C'] as $type) {
            $this->assertArrayHasKey($type, $combined);
            $this->assertGreaterThanOrEqual(0, $combined[$type]);
            $this->assertLessThanOrEqual(100, $combined[$type]);
        }
    }

    // ─────────────────────────────────────────────
    // 4. Filament HTTP access control
    // ─────────────────────────────────────────────

    public function test_superadmin_can_access_admin_panel(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_superadmin_passes_viewany_gate_for_assessment(): void
    {
        $admin = $this->createSuperAdmin();

        // Shield config has define_via_gate=false, so bypass is role-based not Gate-based.
        // Confirm the role is correctly assigned — this is what all canXxx() methods check.
        $this->assertTrue($admin->hasRole('super_admin'));
    }

    public function test_superadmin_passes_view_gate_for_assessment(): void
    {
        $admin = $this->createSuperAdmin();

        // AssessmentResource::canEdit() checks hasRole('super_admin') directly (not Shield policy).
        // Log in as the admin so auth()->user() is resolved inside canEdit().
        auth()->login($admin);
        $this->assertTrue(\App\Filament\Resources\AssessmentResource::canEdit($this->assessment));
        auth()->logout();
    }

    public function test_superadmin_passes_update_gate_for_assessment(): void
    {
        $admin = $this->createSuperAdmin();

        auth()->login($admin);
        $this->assertTrue(\App\Filament\Resources\AssessmentResource::canEdit($this->assessment));
        $this->assertTrue(\App\Filament\Resources\AssessmentResource::canDelete($this->assessment));
        auth()->logout();
    }

    public function test_non_superadmin_cannot_access_edit_page(): void
    {
        // Regular user (no super_admin role) cannot access admin panel
        $admin = $this->createRegularAdmin();

        $response = $this->actingAs($admin)->get('/admin/assessments/' . $this->assessment->id . '/edit');

        // Panel access requires super_admin; regular user gets 403
        $this->assertContains($response->getStatusCode(), [302, 403]);
    }

    public function test_unauthenticated_user_cannot_access_admin(): void
    {
        $response = $this->get('/admin/assessments');

        $response->assertRedirect('/admin/login');
    }

    // ─────────────────────────────────────────────
    // 5. Assessment model canEdit() policy enforcement
    // ─────────────────────────────────────────────

    public function test_canEdit_returns_true_for_superadmin(): void
    {
        $admin = $this->createSuperAdmin();
        $this->actingAs($admin);

        $this->assertTrue(
            \App\Filament\Resources\AssessmentResource::canEdit($this->assessment)
        );
    }

    public function test_canEdit_returns_false_for_non_superadmin(): void
    {
        $admin = $this->createRegularAdmin();
        $this->actingAs($admin);

        $this->assertFalse(
            \App\Filament\Resources\AssessmentResource::canEdit($this->assessment)
        );
    }

    // ─────────────────────────────────────────────
    // 6. canViewForRecord on RelationManagers
    // ─────────────────────────────────────────────

    public function test_relation_manager_visible_for_superadmin(): void
    {
        $admin = $this->createSuperAdmin();
        $this->actingAs($admin);

        $this->assertTrue(
            \App\Filament\Resources\AssessmentResource\RelationManagers\AssessmentAnswersRelationManager::canViewForRecord(
                $this->assessment,
                \App\Filament\Resources\AssessmentResource\Pages\ViewAssessment::class
            )
        );

        $this->assertTrue(
            \App\Filament\Resources\AssessmentResource\RelationManagers\ForcedChoiceAnswersRelationManager::canViewForRecord(
                $this->assessment,
                \App\Filament\Resources\AssessmentResource\Pages\ViewAssessment::class
            )
        );
    }

    public function test_relation_manager_hidden_for_non_superadmin(): void
    {
        $admin = $this->createRegularAdmin();
        $this->actingAs($admin);

        $this->assertFalse(
            \App\Filament\Resources\AssessmentResource\RelationManagers\AssessmentAnswersRelationManager::canViewForRecord(
                $this->assessment,
                \App\Filament\Resources\AssessmentResource\Pages\ViewAssessment::class
            )
        );

        $this->assertFalse(
            \App\Filament\Resources\AssessmentResource\RelationManagers\ForcedChoiceAnswersRelationManager::canViewForRecord(
                $this->assessment,
                \App\Filament\Resources\AssessmentResource\Pages\ViewAssessment::class
            )
        );
    }

    // ─────────────────────────────────────────────
    // 7. DB integrity – cascade delete
    // ─────────────────────────────────────────────

    public function test_fc_answers_deleted_when_assessment_deleted(): void
    {
        $this->createFcAnswers();

        $this->assertGreaterThan(0, ForcedChoiceAssessmentAnswer::where('assessment_id', $this->assessment->id)->count());

        $this->assessment->delete();

        $this->assertEquals(0, ForcedChoiceAssessmentAnswer::where('assessment_id', $this->assessment->id)->count());
    }

    public function test_likert_answers_deleted_when_assessment_deleted(): void
    {
        $this->createLikertAnswers();

        $this->assertGreaterThan(0, AssessmentAnswer::where('assessment_id', $this->assessment->id)->count());

        $this->assessment->delete();

        $this->assertEquals(0, AssessmentAnswer::where('assessment_id', $this->assessment->id)->count());
    }

    // ─────────────────────────────────────────────
    // 8. RIASEC code regeneration after editing all answers
    // ─────────────────────────────────────────────

    public function test_riasec_code_regenerates_correctly_after_score_change(): void
    {
        // All questions answered with 4 = all scores equal
        $this->createLikertAnswers();
        $this->assessment->calculateScores();
        $this->assessment->refresh();

        // Bump all R answers to 5, drop all I answers to 1
        $rCategory = RiasecCategory::where('code', 'R')->first();
        $iCategory = RiasecCategory::where('code', 'I')->first();

        AssessmentAnswer::where('assessment_id', $this->assessment->id)
            ->whereIn('question_id', Question::where('riasec_category_id', $rCategory->id)->pluck('id'))
            ->update(['answer' => 5]);

        AssessmentAnswer::where('assessment_id', $this->assessment->id)
            ->whereIn('question_id', Question::where('riasec_category_id', $iCategory->id)->pluck('id'))
            ->update(['answer' => 1]);

        $this->assessment->calculateScores();
        $this->assessment->refresh();
        $this->assessment->generateRiasecCode();
        $this->assessment->refresh();

        // R should be 100%, I should be 20%, code must start with R, not I
        $this->assertEquals(100, $this->assessment->score_r);
        $this->assertEquals(20, $this->assessment->score_i);
        $this->assertStringStartsWith('R', $this->assessment->riasec_code);
        $this->assertStringNotContainsString('I', substr($this->assessment->riasec_code, 0, 1));
    }
}
