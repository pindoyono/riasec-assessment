<?php

use App\Http\Controllers\AssessmentPdfController;
use App\Livewire\AssessmentResult;
use App\Livewire\StudentLogin;
use App\Livewire\StudentRegistration;
use App\Livewire\TakeAssessment;
use App\Livewire\TakeForcedChoiceAssessment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('assessment.login');
});

// Assessment routes for students
Route::get('/assessment', StudentLogin::class)->name('assessment.login');
Route::get('/assessment/register', StudentRegistration::class)->name('assessment.register');
Route::get('/assessment/take/{assessmentCode}', TakeAssessment::class)->name('assessment.take');
Route::get('/assessment/result/{assessmentCode}', AssessmentResult::class)->name('assessment.result');
Route::get('/assessment/pdf/{assessment}', [AssessmentPdfController::class, 'download'])->name('assessment.pdf');
Route::get('/assessment/pdf-preview/{assessment}', [AssessmentPdfController::class, 'preview'])->name('assessment.pdf.preview');

// Forced Choice routes
Route::get('/assessment/forced-choice/{assessmentCode}', TakeForcedChoiceAssessment::class)->name('assessment.forced-choice.take');
