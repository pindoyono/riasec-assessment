<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@riasec.test',
            'password' => Hash::make('password'),
        ]);

        // Seed RIASEC data
        $this->call([
            RiasecCategorySeeder::class,
            QuestionSeeder::class,
            SmkMajorSeeder::class,
            KaltaraSchoolUserSeeder::class,
        ]);
    }
}
