<?php

namespace Database\Seeders;

use App\Models\RiasecCategory;
use Illuminate\Database\Seeder;

class RiasecCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code' => 'R',
                'name' => 'Realistic',
                'description' => 'Tipe Realistik adalah individu yang menyukai pekerjaan yang melibatkan aktivitas fisik dan praktis. Mereka lebih suka bekerja dengan benda, mesin, alat, tanaman, atau hewan daripada bekerja dengan ide atau manusia.',
                'characteristics' => 'Praktis, mekanis, fisik, terampil dengan alat, suka kegiatan outdoor, lebih suka tindakan daripada bicara, langsung, stabil, mandiri.',
                'preferred_activities' => 'Memperbaiki mesin, merakit peralatan, bekerja dengan tangan, kegiatan di luar ruangan, mengoperasikan peralatan berat, pertukangan, pertanian, olahraga.',
                'strengths' => 'Kemampuan mekanik yang baik, koordinasi fisik, keterampilan praktis, ketahanan fisik, kemampuan memecahkan masalah konkret.',
                'color' => '#ef4444',
                'icon' => 'heroicon-o-wrench-screwdriver',
                'order' => 1,
            ],
            [
                'code' => 'I',
                'name' => 'Investigative',
                'description' => 'Tipe Investigatif adalah individu yang menyukai aktivitas yang melibatkan berpikir, mengamati, menyelidiki, dan memecahkan masalah. Mereka cenderung analitis, intelektual, dan ilmiah.',
                'characteristics' => 'Analitis, intelektual, penasaran, metodis, rasional, independen, introspektif, presisi, suka mengeksplorasi ide.',
                'preferred_activities' => 'Meneliti, menganalisis data, melakukan eksperimen, memecahkan masalah kompleks, membaca, belajar hal baru, menulis ilmiah.',
                'strengths' => 'Kemampuan analitis tinggi, berpikir kritis, matematika dan sains, riset, pemecahan masalah kompleks, ketelitian.',
                'color' => '#3b82f6',
                'icon' => 'heroicon-o-magnifying-glass',
                'order' => 2,
            ],
            [
                'code' => 'A',
                'name' => 'Artistic',
                'description' => 'Tipe Artistik adalah individu yang menyukai aktivitas kreatif dan mengekspresikan diri. Mereka cenderung imajinatif, orisinal, dan tidak terstruktur dalam cara berpikir dan bekerja.',
                'characteristics' => 'Kreatif, imajinatif, ekspresif, orisinal, intuitif, sensitif, independen, tidak konvensional, emosional, idealis.',
                'preferred_activities' => 'Menggambar, melukis, menulis, mendesain, musik, drama, fotografi, seni rupa, kerajinan tangan, dekorasi.',
                'strengths' => 'Kreativitas tinggi, imajinasi, ekspresi diri, kemampuan estetika, berpikir out of the box, sensitivitas terhadap keindahan.',
                'color' => '#eab308',
                'icon' => 'heroicon-o-paint-brush',
                'order' => 3,
            ],
            [
                'code' => 'S',
                'name' => 'Social',
                'description' => 'Tipe Sosial adalah individu yang menyukai aktivitas yang melibatkan membantu, mengajar, atau melayani orang lain. Mereka cenderung kooperatif, suportif, dan peduli terhadap kesejahteraan orang lain.',
                'characteristics' => 'Ramah, kooperatif, empatik, perhatian, suka menolong, sabar, pengertian, komunikatif, hangat, bertanggung jawab.',
                'preferred_activities' => 'Mengajar, konseling, merawat orang sakit, kegiatan sosial, kerja tim, membantu orang lain, kegiatan komunitas, volunteering.',
                'strengths' => 'Keterampilan interpersonal, empati tinggi, komunikasi, kemampuan mendengar, kesabaran, membangun hubungan, kerja sama tim.',
                'color' => '#22c55e',
                'icon' => 'heroicon-o-users',
                'order' => 4,
            ],
            [
                'code' => 'E',
                'name' => 'Enterprising',
                'description' => 'Tipe Enterprising adalah individu yang menyukai aktivitas yang melibatkan memimpin, mempengaruhi, dan membujuk orang lain. Mereka cenderung ambisius, energik, dan percaya diri.',
                'characteristics' => 'Ambisius, energik, percaya diri, persuasif, kompetitif, berani mengambil risiko, optimis, dominan, berorientasi pada hasil.',
                'preferred_activities' => 'Memimpin tim, berbicara di depan umum, negosiasi, penjualan, memulai bisnis, politik, manajemen, marketing.',
                'strengths' => 'Kepemimpinan, persuasi, pengambilan keputusan, negosiasi, public speaking, networking, manajemen, entrepreneurship.',
                'color' => '#8b5cf6',
                'icon' => 'heroicon-o-briefcase',
                'order' => 5,
            ],
            [
                'code' => 'C',
                'name' => 'Conventional',
                'description' => 'Tipe Konvensional adalah individu yang menyukai aktivitas yang terstruktur dan mengikuti aturan. Mereka cenderung terorganisir, teliti, dan menyukai pekerjaan dengan prosedur yang jelas.',
                'characteristics' => 'Terorganisir, teliti, sistematis, patuh aturan, efisien, praktis, akurat, bertanggung jawab, dapat diandalkan, konservatif.',
                'preferred_activities' => 'Pengolahan data, administrasi, pembukuan, pengarsipan, penjadwalan, mengikuti prosedur, pengelolaan keuangan.',
                'strengths' => 'Ketelitian tinggi, organisasi, manajemen data, akurasi numerik, efisiensi, kepatuhan prosedur, konsistensi.',
                'color' => '#6b7280',
                'icon' => 'heroicon-o-calculator',
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            RiasecCategory::create($category);
        }
    }
}
