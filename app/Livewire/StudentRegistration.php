<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\Student;
use Livewire\Component;

class StudentRegistration extends Component
{
    public string $error = '';
    public string $success = '';

    // Form fields
    public string $nisn = '';
    public string $name = '';
    public string $gender = '';
    public string $birth_place = '';
    public ?string $birth_date = null;
    public string $asal_sekolah = '';
    public ?string $school_id = null;
    public string $class = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $parent_name = '';
    public string $parent_phone = '';

    public bool $registered = false;

    public function register()
    {
        $this->validate([
            'nisn' => ['required', 'regex:/^\d{10}$/', 'unique:students,nisn'],
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'school_id' => 'required|exists:schools,id',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'asal_sekolah' => 'nullable|string|max:255',
            'class' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.regex' => 'NISN harus 10 digit angka.',
            'nisn.unique' => 'NISN sudah terdaftar. Silakan langsung login.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'school_id.required' => 'Lokasi test wajib dipilih.',
            'school_id.exists' => 'Lokasi test tidak valid.',
            'birth_date.before' => 'Tanggal lahir tidak valid.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $this->error = '';

        Student::create([
            'nisn' => $this->nisn,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_place' => $this->birth_place ?: null,
            'birth_date' => $this->birth_date ?: null,
            'asal_sekolah' => $this->asal_sekolah ?: null,
            'school_id' => $this->school_id,
            'class' => $this->class ?: null,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address ?: null,
            'parent_name' => $this->parent_name ?: null,
            'parent_phone' => $this->parent_phone ?: null,
            'is_active' => true,
        ]);

        $this->registered = true;
        $this->success = 'Registrasi berhasil!';
    }

    public function goToLogin()
    {
        return redirect()->route('assessment.login');
    }

    public function render()
    {
        return view('livewire.student-registration', [
            'schools' => School::where('is_active', true)->orderBy('name')->get(),
        ])
            ->layout('layouts.assessment');
    }
}
