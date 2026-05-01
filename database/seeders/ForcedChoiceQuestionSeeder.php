<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForcedChoiceQuestion;
use Illuminate\Support\Facades\DB;

class ForcedChoiceQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks (compatible with both MySQL and SQLite)
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        ForcedChoiceQuestion::truncate();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }

        $questions = [
            // ── R vs S ──
            ['option_a_text' => 'Memperbaiki alat elektronik', 'option_a_type' => 'R', 'option_b_text' => 'Membantu orang belajar', 'option_b_type' => 'S'],
            ['option_a_text' => 'Bekerja dengan mesin', 'option_a_type' => 'R', 'option_b_text' => 'Memberi pelayanan kepada orang', 'option_b_type' => 'S'],

            // ── I vs A ──
            ['option_a_text' => 'Menganalisis data', 'option_a_type' => 'I', 'option_b_text' => 'Membuat desain/gambar', 'option_b_type' => 'A'],
            ['option_a_text' => 'Melakukan eksperimen', 'option_a_type' => 'I', 'option_b_text' => 'Menulis cerita/konten', 'option_b_type' => 'A'],

            // ── A vs C ──
            ['option_a_text' => 'Mendesain poster', 'option_a_type' => 'A', 'option_b_text' => 'Mengelola data di Excel', 'option_b_type' => 'C'],
            ['option_a_text' => 'Membuat ilustrasi', 'option_a_type' => 'A', 'option_b_text' => 'Mengarsipkan dokumen', 'option_b_type' => 'C'],

            // ── S vs E ──
            ['option_a_text' => 'Mengajar orang lain', 'option_a_type' => 'S', 'option_b_text' => 'Memimpin tim', 'option_b_type' => 'E'],
            ['option_a_text' => 'Konseling teman', 'option_a_type' => 'S', 'option_b_text' => 'Menjual produk', 'option_b_type' => 'E'],

            // ── E vs R ──
            ['option_a_text' => 'Membuka usaha', 'option_a_type' => 'E', 'option_b_text' => 'Bekerja di bengkel', 'option_b_type' => 'R'],
            ['option_a_text' => 'Negosiasi bisnis', 'option_a_type' => 'E', 'option_b_text' => 'Mengoperasikan alat', 'option_b_type' => 'R'],

            // ── C vs I ──
            ['option_a_text' => 'Mengelola administrasi', 'option_a_type' => 'C', 'option_b_text' => 'Meneliti sesuatu', 'option_b_type' => 'I'],
            ['option_a_text' => 'Input data', 'option_a_type' => 'C', 'option_b_text' => 'Analisis masalah', 'option_b_type' => 'I'],

            // ── R vs A ──
            ['option_a_text' => 'Merakit komponen teknis', 'option_a_type' => 'R', 'option_b_text' => 'Membuat karya seni', 'option_b_type' => 'A'],
            ['option_a_text' => 'Bekerja di lapangan', 'option_a_type' => 'R', 'option_b_text' => 'Membuat musik/lagu', 'option_b_type' => 'A'],

            // ── R vs I ──
            ['option_a_text' => 'Mengoperasikan mesin produksi', 'option_a_type' => 'R', 'option_b_text' => 'Meneliti cara kerja mesin', 'option_b_type' => 'I'],
            ['option_a_text' => 'Membangun instalasi listrik', 'option_a_type' => 'R', 'option_b_text' => 'Membuat formula kimia', 'option_b_type' => 'I'],

            // ── R vs C ──
            ['option_a_text' => 'Memperbaiki kendaraan', 'option_a_type' => 'R', 'option_b_text' => 'Merapikan laporan keuangan', 'option_b_type' => 'C'],
            ['option_a_text' => 'Bertani/berkebun', 'option_a_type' => 'R', 'option_b_text' => 'Mengelola inventaris', 'option_b_type' => 'C'],

            // ── I vs S ──
            ['option_a_text' => 'Mengembangkan teknologi baru', 'option_a_type' => 'I', 'option_b_text' => 'Melatih orang baru', 'option_b_type' => 'S'],
            ['option_a_text' => 'Menulis karya ilmiah', 'option_a_type' => 'I', 'option_b_text' => 'Membantu seseorang yang kesulitan', 'option_b_type' => 'S'],

            // ── I vs E ──
            ['option_a_text' => 'Melakukan riset pasar', 'option_a_type' => 'I', 'option_b_text' => 'Memimpin tim penjualan', 'option_b_type' => 'E'],
            ['option_a_text' => 'Mengembangkan algoritma', 'option_a_type' => 'I', 'option_b_text' => 'Merancang strategi bisnis', 'option_b_type' => 'E'],

            // ── A vs S ──
            ['option_a_text' => 'Membuat film pendek', 'option_a_type' => 'A', 'option_b_text' => 'Menjadi konselor remaja', 'option_b_type' => 'S'],
            ['option_a_text' => 'Mendekorasi ruangan', 'option_a_type' => 'A', 'option_b_text' => 'Mengorganisir kegiatan sosial', 'option_b_type' => 'S'],

            // ── A vs E ──
            ['option_a_text' => 'Menulis naskah drama', 'option_a_type' => 'A', 'option_b_text' => 'Mempresentasikan ide kepada klien', 'option_b_type' => 'E'],
            ['option_a_text' => 'Mendesain identitas merek', 'option_a_type' => 'A', 'option_b_text' => 'Memasarkan produk baru', 'option_b_type' => 'E'],

            // ── S vs C ──
            ['option_a_text' => 'Memberikan bimbingan karir', 'option_a_type' => 'S', 'option_b_text' => 'Menyusun jadwal kegiatan kantor', 'option_b_type' => 'C'],
            ['option_a_text' => 'Menjadi relawan sosial', 'option_a_type' => 'S', 'option_b_text' => 'Mengelola database karyawan', 'option_b_type' => 'C'],

            // ── E vs C ──
            ['option_a_text' => 'Mendirikan startup', 'option_a_type' => 'E', 'option_b_text' => 'Menyusun laporan keuangan', 'option_b_type' => 'C'],
            ['option_a_text' => 'Memenangkan kontrak bisnis', 'option_a_type' => 'E', 'option_b_text' => 'Mengatur prosedur administrasi', 'option_b_type' => 'C'],
        ];

        foreach ($questions as $index => $q) {
            ForcedChoiceQuestion::create([
                'prompt'         => 'Pilih aktivitas yang lebih kamu sukai',
                'option_a_text'  => $q['option_a_text'],
                'option_a_type'  => $q['option_a_type'],
                'option_b_text'  => $q['option_b_text'],
                'option_b_type'  => $q['option_b_type'],
                'order'          => $index + 1,
                'is_active'      => true,
            ]);
        }
    }
}
