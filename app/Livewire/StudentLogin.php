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
    public bool $nisnValidated = false;
    public ?Student $student = null;
    public ?School $school = null;

    public function checkNisn()
    {
        $this->validate([
            'nisn' => 'required|digits:10',
        ]);

        $this->error = '';
        $student = Student::where('nisn', $this->nisn)->first();

        if (!$student) {
            return redirect()->route('assessment.register', ['nisn' => $this->nisn]);
        }

        if (!$student->is_active) {
            $this->error = 'Akun siswa tidak aktif. Hubungi admin.';
            return;
        }

        $completedAssessment = $student->assessments()
            ->where('status', 'completed')
            ->latest('completed_at')
            ->latest('id')
            ->first();

        if ($completedAssessment) {
            return redirect()->route('assessment.result', [
                'assessmentCode' => $completedAssessment->assessment_code,
            ]);
        }

        $this->student = $student;
        $this->nisnValidated = true;
    }

    public function login()
    {
        $this->validate([
            'token' => 'required|min:6|max:10',
        ]);

        $this->error = '';

        if (!$this->student) {
            $this->error = 'Silakan cek NISN terlebih dahulu.';
            $this->nisnValidated = false;
            return;
        }

        $school = School::findByValidToken(strtoupper($this->token));

        if (!$school) {
            $this->error = 'Token tidak valid, sudah expired, atau tidak ditemukan.';
            return;
        }

        if ($this->student->school_id !== $school->id) {
            $this->error = 'Token tidak sesuai dengan lokasi test siswa ini.';
            return;
        }

        $this->school = $school;

        $redirectRoute = 'assessment.take';
        $redirectParams = [];

        DB::transaction(function () use (&$redirectRoute, &$redirectParams) {
            // Lock student row to prevent race condition from concurrent login requests.
            $lockedStudent = Student::query()
                ->whereKey($this->student->id)
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

    public function backToNisn()
    {
        $this->nisnValidated = false;
        $this->school = null;
        $this->student = null;
        $this->token = '';
        $this->error = '';
    }

    public function render()
    {
        return view('livewire.student-login')
            ->layout('layouts.assessment');
    }
}
