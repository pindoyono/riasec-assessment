<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentLogin extends Component
{
    public string $token = '';
    public string $nisn = '';
    public string $error = '';
    public bool $tokenValidated = false;
    public ?School $school = null;

    public function validateToken()
    {
        $this->validate([
            'token' => 'required|min:6|max:10',
        ]);

        $this->error = '';
        $school = School::findByValidToken(strtoupper($this->token));

        if (!$school) {
            $this->error = 'Token tidak valid, sudah expired, atau tidak ditemukan.';
            return;
        }

        $this->school = $school;
        $this->tokenValidated = true;
    }

    public function login()
    {
        $this->validate([
            'nisn' => 'required|digits:10',
        ]);

        $this->error = '';

        // Re-validate school token
        if (!$this->school || !$this->school->isTokenValid()) {
            $this->error = 'Token sudah expired. Silakan minta token baru ke admin.';
            $this->tokenValidated = false;
            $this->school = null;
            return;
        }

        // Find student by NISN and school
        $student = Student::where('nisn', $this->nisn)
            ->where('school_id', $this->school->id)
            ->first();

        if (!$student) {
            $this->error = 'NISN tidak ditemukan di lokasi test ini. Pastikan NISN benar dan Anda terdaftar di lokasi ini.';
            return;
        }

        if (!$student->is_active) {
            $this->error = 'Akun siswa tidak aktif. Hubungi admin.';
            return;
        }

        $redirectRoute = 'assessment.take';
        $redirectParams = [];

        DB::transaction(function () use ($student, &$redirectRoute, &$redirectParams) {
            // Lock student row to prevent race condition from concurrent login requests.
            $lockedStudent = Student::query()
                ->whereKey($student->id)
                ->lockForUpdate()
                ->firstOrFail();

            $completedAssessment = $lockedStudent->assessments()
                ->where('status', 'completed')
                ->latest('completed_at')
                ->latest('id')
                ->first();

            if ($completedAssessment) {
                $redirectRoute = 'assessment.result';
                $redirectParams = [
                    'assessmentCode' => $completedAssessment->assessment_code,
                ];
                return;
            }

            $assessment = $lockedStudent->assessments()
                ->whereIn('status', ['pending', 'in_progress'])
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (!$assessment) {
                $assessment = $lockedStudent->assessments()->create([
                    'status' => 'pending',
                ]);
            }

            $redirectRoute = 'assessment.take';
            $redirectParams = [
                'assessmentCode' => $assessment->assessment_code,
            ];
        });

        return redirect()->route($redirectRoute, $redirectParams);
    }

    public function backToToken()
    {
        $this->tokenValidated = false;
        $this->school = null;
        $this->nisn = '';
        $this->error = '';
    }

    public function render()
    {
        return view('livewire.student-login')
            ->layout('layouts.assessment');
    }
}
