<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class KaltaraSchoolUserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role ada
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // ============================================================
        // Super Admin
        // ============================================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@riasec.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        // ============================================================
        // SMK Negeri & Swasta se-Kalimantan Utara
        // Kabupaten/Kota: Tarakan, Nunukan, Malinau, Bulungan, Tana Tidung
        // ============================================================
        $schools = [
            // === KOTA TARAKAN ===
            ['name' => 'SMKN 1 Tarakan', 'npsn' => '30400717', 'type' => 'smk', 'district' => 'Tarakan Tengah', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 2 Tarakan', 'npsn' => '30400716', 'type' => 'smk', 'district' => 'Tarakan Tengah', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 3 Tarakan', 'npsn' => '30400715', 'type' => 'smk', 'district' => 'Tarakan Barat', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 4 Tarakan', 'npsn' => '30400714', 'type' => 'smk', 'district' => 'Tarakan Utara', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 5 Tarakan', 'npsn' => '69964438', 'type' => 'smk', 'district' => 'Tarakan Timur', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Vidya Taruna Tarakan', 'npsn' => '30400700', 'type' => 'smk', 'district' => 'Tarakan Tengah', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Kartini Tarakan', 'npsn' => '30400706', 'type' => 'smk', 'district' => 'Tarakan Tengah', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta YPPK Tarakan', 'npsn' => '30400701', 'type' => 'smk', 'district' => 'Tarakan Tengah', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Muhammadiyah 1 Tarakan', 'npsn' => '30400704', 'type' => 'smk', 'district' => 'Tarakan Tengah', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Tunas Bangsa Tarakan', 'npsn' => '30400702', 'type' => 'smk', 'district' => 'Tarakan Barat', 'city' => 'Kota Tarakan', 'province' => 'Kalimantan Utara'],

            // === KABUPATEN NUNUKAN ===
            ['name' => 'SMKN 1 Nunukan', 'npsn' => '30400671', 'type' => 'smk', 'district' => 'Nunukan', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 2 Nunukan', 'npsn' => '69835117', 'type' => 'smk', 'district' => 'Nunukan', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Sebatik', 'npsn' => '30400672', 'type' => 'smk', 'district' => 'Sebatik', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Lumbis Ogong', 'npsn' => '69964440', 'type' => 'smk', 'district' => 'Lumbis Ogong', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Sei Menggaris', 'npsn' => '69964441', 'type' => 'smk', 'district' => 'Sei Menggaris', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Krayan', 'npsn' => '69964442', 'type' => 'smk', 'district' => 'Krayan', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Darul Ulum Nunukan', 'npsn' => '69835118', 'type' => 'smk', 'district' => 'Nunukan', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Muhammadiyah Nunukan', 'npsn' => '69835119', 'type' => 'smk', 'district' => 'Nunukan', 'city' => 'Kabupaten Nunukan', 'province' => 'Kalimantan Utara'],

            // === KABUPATEN MALINAU ===
            ['name' => 'SMKN 1 Malinau', 'npsn' => '30400687', 'type' => 'smk', 'district' => 'Malinau Kota', 'city' => 'Kabupaten Malinau', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 2 Malinau', 'npsn' => '69835120', 'type' => 'smk', 'district' => 'Malinau Kota', 'city' => 'Kabupaten Malinau', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Kayan Hulu', 'npsn' => '69964443', 'type' => 'smk', 'district' => 'Kayan Hulu', 'city' => 'Kabupaten Malinau', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Mentarang', 'npsn' => '69964444', 'type' => 'smk', 'district' => 'Mentarang', 'city' => 'Kabupaten Malinau', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Bina Karya Malinau', 'npsn' => '69835121', 'type' => 'smk', 'district' => 'Malinau Kota', 'city' => 'Kabupaten Malinau', 'province' => 'Kalimantan Utara'],

            // === KABUPATEN BULUNGAN ===
            ['name' => 'SMKN 1 Tanjung Selor', 'npsn' => '30400693', 'type' => 'smk', 'district' => 'Tanjung Selor', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 2 Tanjung Selor', 'npsn' => '30400692', 'type' => 'smk', 'district' => 'Tanjung Selor', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 3 Tanjung Selor', 'npsn' => '69835122', 'type' => 'smk', 'district' => 'Tanjung Selor', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Tanjung Palas', 'npsn' => '30400694', 'type' => 'smk', 'district' => 'Tanjung Palas', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Sekatak', 'npsn' => '69964445', 'type' => 'smk', 'district' => 'Sekatak', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 1 Peso', 'npsn' => '69964446', 'type' => 'smk', 'district' => 'Peso', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta PGRI Tanjung Selor', 'npsn' => '30400689', 'type' => 'smk', 'district' => 'Tanjung Selor', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Vidya Taruna Tanjung Selor', 'npsn' => '69835123', 'type' => 'smk', 'district' => 'Tanjung Selor', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Muhammadiyah Tanjung Selor', 'npsn' => '69835124', 'type' => 'smk', 'district' => 'Tanjung Selor', 'city' => 'Kabupaten Bulungan', 'province' => 'Kalimantan Utara'],

            // === KABUPATEN TANA TIDUNG ===
            ['name' => 'SMKN 1 Tana Tidung', 'npsn' => '69835125', 'type' => 'smk', 'district' => 'Tideng Pale', 'city' => 'Kabupaten Tana Tidung', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMKN 2 Tana Tidung', 'npsn' => '69964447', 'type' => 'smk', 'district' => 'Sesayap', 'city' => 'Kabupaten Tana Tidung', 'province' => 'Kalimantan Utara'],
            ['name' => 'SMK Swasta Tideng Pale', 'npsn' => '69835126', 'type' => 'smk', 'district' => 'Tideng Pale', 'city' => 'Kabupaten Tana Tidung', 'province' => 'Kalimantan Utara'],
        ];

        // ============================================================
        // Seed Schools & create operator user per school
        // ============================================================
        foreach ($schools as $schoolData) {
            $school = School::firstOrCreate(
                ['npsn' => $schoolData['npsn']],
                array_merge($schoolData, [
                    'is_active' => true,
                    'token_valid_minutes' => 120,
                ])
            );

            // Create operator user for each school
            $emailSlug = strtolower(str_replace([' ', '.'], ['', ''], $schoolData['name']));
            $emailSlug = substr($emailSlug, 0, 30);
            $email = $emailSlug . '@riasec.test';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Operator ' . $schoolData['name'],
                    'password' => Hash::make('password'),
                    'school_id' => $school->id,
                    'is_active' => true,
                ]
            );

            // Generate token for school if not exists
            if (empty($school->registration_token)) {
                $school->generateToken();
            }
        }
    }
}
