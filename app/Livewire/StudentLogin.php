<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\Student;
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

        // Check if student has already completed assessment
        if ($student->hasCompletedAssessment()) {
            // Redirect to result page
            return redirect()->route('assessment.result', [
                'assessmentCode' => $student->completedAssessment->assessment_code,
            ]);
        }

        // Get or create pending assessment
        $assessment = $student->assessments()
            ->whereIn('status', ['pending', 'in_progress'])
            ->first();

        if (!$assessment) {
            $assessment = $student->assessments()->create([
                'status' => 'pending',
            ]);
        }

        // Redirect to assessment page
        return redirect()->route('assessment.take', [
            'assessmentCode' => $assessment->assessment_code,
        ]);
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
