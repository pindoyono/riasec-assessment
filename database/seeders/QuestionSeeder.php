<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\RiasecCategory;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            // REALISTIC (R) - 10 pertanyaan
            'R' => [
                'Saya suka memperbaiki barang-barang yang rusak di rumah.',
                'Saya lebih suka bekerja menggunakan alat dan mesin daripada duduk di depan komputer.',
                'Saya menikmati kegiatan di luar ruangan seperti berkebun atau membangun sesuatu.',
                'Saya merasa puas ketika bisa membuat atau merakit sesuatu dengan tangan saya sendiri.',
                'Saya tertarik untuk mempelajari cara kerja mesin atau peralatan elektronik.',
                'Saya lebih suka pekerjaan yang hasilnya bisa langsung terlihat secara fisik.',
                'Saya suka berolahraga atau melakukan aktivitas fisik secara teratur.',
                'Saya tertarik dengan bidang teknik, mekanik, atau pertukangan.',
                'Saya lebih nyaman bekerja dengan benda-benda konkret daripada konsep abstrak.',
                'Saya suka mengutak-atik kendaraan, komputer, atau peralatan lainnya.',
            ],

            // INVESTIGATIVE (I) - 10 pertanyaan
            'I' => [
                'Saya suka memecahkan masalah yang rumit dan menantang.',
                'Saya menikmati membaca buku atau artikel tentang sains dan teknologi.',
                'Saya senang menganalisis data untuk menemukan pola atau tren.',
                'Saya tertarik untuk memahami bagaimana sesuatu bekerja secara mendalam.',
                'Saya suka melakukan eksperimen atau percobaan untuk menguji ide.',
                'Saya lebih suka berpikir dan merencanakan sebelum bertindak.',
                'Saya menikmati matematika dan perhitungan yang kompleks.',
                'Saya selalu penasaran dan suka mengajukan pertanyaan "mengapa".',
                'Saya tertarik dengan penelitian dan penemuan ilmiah baru.',
                'Saya suka menghabiskan waktu untuk belajar hal-hal baru secara mandiri.',
            ],

            // ARTISTIC (A) - 10 pertanyaan
            'A' => [
                'Saya suka mengekspresikan diri melalui seni, musik, atau tulisan.',
                'Saya menikmati kegiatan kreatif seperti menggambar, melukis, atau desain.',
                'Saya memiliki imajinasi yang kuat dan sering bermimpi tentang ide-ide baru.',
                'Saya lebih suka pekerjaan yang memungkinkan kebebasan berekspresi.',
                'Saya tertarik dengan dunia seni, fashion, atau hiburan.',
                'Saya suka menciptakan sesuatu yang orisinal dan unik.',
                'Saya merasa terinspirasi oleh keindahan alam atau karya seni.',
                'Saya lebih suka lingkungan kerja yang fleksibel dan tidak kaku.',
                'Saya suka bermain musik, menulis cerita, atau membuat karya seni.',
                'Saya memiliki apresiasi tinggi terhadap estetika dan keindahan.',
            ],

            // SOCIAL (S) - 10 pertanyaan
            'S' => [
                'Saya suka membantu orang lain memecahkan masalah mereka.',
                'Saya menikmati bekerja dalam tim dan berkolaborasi dengan orang lain.',
                'Saya tertarik dengan profesi yang melibatkan mengajar atau melatih orang.',
                'Saya merasa senang ketika bisa membuat orang lain merasa lebih baik.',
                'Saya mudah berempati dan memahami perasaan orang lain.',
                'Saya suka terlibat dalam kegiatan sosial atau komunitas.',
                'Saya lebih suka berkomunikasi secara langsung daripada melalui tulisan.',
                'Saya tertarik dengan bidang kesehatan, pendidikan, atau pelayanan sosial.',
                'Saya merasa puas ketika bisa memberikan nasihat yang membantu orang lain.',
                'Saya menikmati mendengarkan cerita dan pengalaman orang lain.',
            ],

            // ENTERPRISING (E) - 10 pertanyaan
            'E' => [
                'Saya suka memimpin dan mengorganisir kegiatan kelompok.',
                'Saya menikmati meyakinkan orang lain untuk menerima ide saya.',
                'Saya tertarik untuk memulai bisnis atau usaha sendiri.',
                'Saya suka mengambil risiko yang terkalkulasi untuk mencapai tujuan.',
                'Saya merasa nyaman berbicara di depan banyak orang.',
                'Saya ambisius dan selalu ingin mencapai prestasi tinggi.',
                'Saya suka negosiasi dan tawar-menawar untuk mendapatkan hasil terbaik.',
                'Saya tertarik dengan dunia bisnis, marketing, atau manajemen.',
                'Saya lebih suka memimpin daripada dipimpin dalam sebuah proyek.',
                'Saya menikmati kompetisi dan tantangan untuk menjadi yang terbaik.',
            ],

            // CONVENTIONAL (C) - 10 pertanyaan
            'C' => [
                'Saya suka pekerjaan yang terstruktur dengan prosedur yang jelas.',
                'Saya menikmati mengorganisir data, file, atau dokumen dengan rapi.',
                'Saya teliti dan cermat dalam mengerjakan tugas-tugas detail.',
                'Saya lebih suka mengikuti aturan dan standar yang sudah ditetapkan.',
                'Saya suka bekerja dengan angka dan perhitungan akuntansi.',
                'Saya merasa nyaman dengan rutinitas dan jadwal kerja yang teratur.',
                'Saya tertarik dengan bidang administrasi, keuangan, atau perbankan.',
                'Saya suka membuat daftar dan perencanaan yang terperinci.',
                'Saya menikmati pekerjaan yang membutuhkan ketelitian dan akurasi.',
                'Saya lebih suka lingkungan kerja yang stabil dan dapat diprediksi.',
            ],
        ];

        foreach ($questions as $categoryCode => $categoryQuestions) {
            $category = RiasecCategory::where('code', $categoryCode)->first();

            if (!$category) {
                continue;
            }

            foreach ($categoryQuestions as $order => $questionText) {
                Question::create([
                    'riasec_category_id' => $category->id,
                    'question_text' => $questionText,
                    'order' => $order + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
