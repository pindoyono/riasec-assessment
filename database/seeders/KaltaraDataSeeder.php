<?php

namespace Database\Seeders;

use App\Models\RiasecCategory;
use App\Models\SmkMajor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaltaraDataSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. Update RIASEC Category descriptions
        // ============================================================
        $riasecData = [
            'R' => [
                'description' => 'Seseorang dengan kepribadi ini menyukai pekerjaan yang melibatkan tindakan daripada berpikir, lebih menyukai hasil nyata atau yang dapat dilihat langsung. Orang dengan tipe kepribadian ini umumnya memiliki rasa ingin tahu yang tinggi tentang sains, benda-benda nyata, dan mekanika.',
            ],
            'I' => [
                'description' => 'Mereka yang termasuk dalam kepribadian Investigative menyukai penggunaan kemampuan abstrak atau analisis untuk menemukan dari masalah yang ada di sekitarnya. Mereka dapat dianggap sebagai "pemikir" yang selalu berusaha menyelesaikan tugas dan sering bekerja secara mandiri. Menurut tes RIASEC Holland, kelompok ini cenderung analitis, suka menggali lebih dalam, dan mencari kebenaran atau fakta dari sebuah informasi.',
            ],
            'A' => [
                'description' => 'Orang yang masuk dalam kepribadian Artistic pasti menyukai kreativitas dan kaya akan imajinasi, tetapi memiliki kepribadian yang sangat impulsif dan suka bekerja mengandalkan perasaan. Kamu mungkin lebih mudah dipengaruhi oleh emosi, lebih didominasi oleh perasaan daripada logika, dan tidak suka bekerja dalam batasan yang ketat. Sering kali, orang-orang yang termasuk dalam kategori ini telah memiliki potensi atau bakat khusus yang menonjol dalam bidang seni.',
            ],
            'S' => [
                'description' => 'Menurut tes RIASEC Holland, mereka yang berkepribadian Social cenderung suka membantu orang lain, berinteraksi, dan berbicara. Mereka peduli pada masalah sosial dan memiliki kemampuan untuk mengekspresikan pendapat dengan baik serta ahli dalam membujuk orang lain. Pada dasarnya, mereka adalah pribadi yang ekstrovert, ramah, dan terbuka. Oleh karena itu bekerja di bidang amal, kegiatan sosial, dan mengajar sangat cocok untuk mereka.',
            ],
            'E' => [
                'description' => 'Orang yang punya kepribadian Enterprising cenderung berani berpikir dan bertindak, condong pada peran kepemimpinan. Mereka bersedia menghadapi tantangan dan menghadapi banyak kesulitan, serta memiliki semangat berjuang. Minat mereka umumnya berfokus pada bisnis, kepemimpinan, manajemen, negosiasi, atau membujuk orang lain. Oleh karena itu, mereka cocok untuk posisi manajemen di dunia bisnis karena berjiwa sosial.',
            ],
            'C' => [
                'description' => 'Menurut tes RIASEC Holland, Conventional adalah tipe orang yang hati-hati, teliti, berprinsip, dan selalu mengikuti aturan. Mereka bekerja dengan angka, laporan data. Mereka cocok dengan pekerjaan kantor, pejabat pemerintah, pekerjaan yang membutuhkan kehati-hatian, detail, serta keteraturan.',
            ],
        ];

        foreach ($riasecData as $code => $data) {
            RiasecCategory::where('code', $code)->update($data);
        }

        // ============================================================
        // 2. Clear and re-seed SmkMajor with KALTARA data
        // ============================================================
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        SmkMajor::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $majors = [
            // ======= REALISTIC =======
            ['code' => 'TKJ', 'name' => 'Teknik Komputer Jaringan', 'program_keahlian' => 'Teknik Jaringan Komputer dan Telekomunikasi', 'bidang_keahlian' => 'Teknologi Informasi', 'riasec_profile' => ['R', 'I']],
            ['code' => 'TOT', 'name' => 'Teknik Otomotif', 'program_keahlian' => 'Teknik Otomotif', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R']],
            ['code' => 'TBKR', 'name' => 'Teknik Bodi Kendaraan Ringan', 'program_keahlian' => 'Teknik Otomotif', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R']],
            ['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak', 'program_keahlian' => 'Pengembangan Perangkat Lunak dan Gim', 'bidang_keahlian' => 'Teknologi Informasi', 'riasec_profile' => ['R', 'I']],
            ['code' => 'TBOG', 'name' => 'Tata Boga', 'program_keahlian' => 'Kuliner', 'bidang_keahlian' => 'Pariwisata', 'riasec_profile' => ['R', 'A', 'S']],
            ['code' => 'TAB', 'name' => 'Teknik Alat Berat', 'program_keahlian' => 'Teknik Otomotif', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R']],
            ['code' => 'KUL', 'name' => 'Kuliner', 'program_keahlian' => 'Kuliner', 'bidang_keahlian' => 'Pariwisata', 'riasec_profile' => ['R', 'S']],
            ['code' => 'TKRO', 'name' => 'Teknik Kendaraan Ringan', 'program_keahlian' => 'Teknik Otomotif', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R']],
            ['code' => 'TITL', 'name' => 'Teknik Instalasi Tenaga Listrik', 'program_keahlian' => 'Teknik Ketenagalistrikan', 'bidang_keahlian' => 'Energi dan Pertambangan', 'riasec_profile' => ['R']],
            ['code' => 'TPL', 'name' => 'Teknik Pengelasan', 'program_keahlian' => 'Teknik Mesin', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R']],
            ['code' => 'TPMK', 'name' => 'Teknik Perminyakan', 'program_keahlian' => 'Teknik Perminyakan', 'bidang_keahlian' => 'Energi dan Pertambangan', 'riasec_profile' => ['R']],
            ['code' => 'TEI', 'name' => 'Teknik Elektronika Industri', 'program_keahlian' => 'Teknik Elektronika', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R']],
            ['code' => 'TPM', 'name' => 'Teknik Pemesinan', 'program_keahlian' => 'Teknik Mesin', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R']],
            ['code' => 'TGP', 'name' => 'Teknik Geologi Pertambangan', 'program_keahlian' => 'Geologi Pertambangan', 'bidang_keahlian' => 'Energi dan Pertambangan', 'riasec_profile' => ['R']],
            ['code' => 'TPTU', 'name' => 'Teknik Pemanasan, Tata Udara, dan Pendinginan', 'program_keahlian' => 'Teknik Pendinginan dan Tata Udara', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R', 'I']],
            ['code' => 'NKPI', 'name' => 'Nautika Kapal Penangkap Ikan', 'program_keahlian' => 'Pelayaran Kapal Penangkap Ikan', 'bidang_keahlian' => 'Kemaritiman', 'riasec_profile' => ['R']],
            ['code' => 'TKPI', 'name' => 'Teknik Kapal Penangkap Ikan', 'program_keahlian' => 'Pelayaran Kapal Penangkap Ikan', 'bidang_keahlian' => 'Kemaritiman', 'riasec_profile' => ['R']],
            ['code' => 'APPL', 'name' => 'Agribisnis Perikanan Air Payau dan Laut', 'program_keahlian' => 'Agribisnis Perikanan', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R', 'I', 'E']],
            ['code' => 'ATPH', 'name' => 'Agribisnis Tanaman Pangan dan Hortikultura', 'program_keahlian' => 'Agribisnis Tanaman', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R']],
            ['code' => 'APAT', 'name' => 'Agribisnis Perikanan Air Tawar', 'program_keahlian' => 'Agribisnis Perikanan', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R', 'I', 'E']],
            ['code' => 'APHP', 'name' => 'Agribisnis Pengolahan Hasil Perikanan', 'program_keahlian' => 'Agroteknologi Pengolahan Hasil Pertanian', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R', 'I', 'E']],
            ['code' => 'APHPT', 'name' => 'Agribisnis Pengolahan Hasil Pertanian', 'program_keahlian' => 'Agroteknologi Pengolahan Hasil Pertanian', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R', 'I', 'E']],
            ['code' => 'ATPK', 'name' => 'Agribisnis Tanaman Perkebunan', 'program_keahlian' => 'Agribisnis Tanaman', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R', 'I', 'E']],
            ['code' => 'ATU', 'name' => 'Agribisnis Ternak Unggas', 'program_keahlian' => 'Agribisnis Ternak', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R', 'I', 'E']],
            ['code' => 'AT', 'name' => 'Agribisnis Tanaman', 'program_keahlian' => 'Agribisnis Tanaman', 'bidang_keahlian' => 'Agribisnis dan Agroteknologi', 'riasec_profile' => ['R', 'I', 'E']],
            ['code' => 'TMK', 'name' => 'Teknik Mekatronika', 'program_keahlian' => 'Teknik Elektronika', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R', 'I']],
            ['code' => 'TKP', 'name' => 'Teknik Konstruksi dan Perumahan', 'program_keahlian' => 'Teknik Konstruksi dan Perumahan', 'bidang_keahlian' => 'Teknologi Konstruksi', 'riasec_profile' => ['R', 'I']],
            ['code' => 'TESHA', 'name' => 'Teknik Energi Surya, Hidro dan Angin', 'program_keahlian' => 'Teknik Energi Terbarukan', 'bidang_keahlian' => 'Energi dan Pertambangan', 'riasec_profile' => ['R', 'I']],
            ['code' => 'TOI', 'name' => 'Teknik Otomasi Industri', 'program_keahlian' => 'Teknik Elektronika', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R', 'I']],
            ['code' => 'TPTU2', 'name' => 'Teknik Pendinginan dan Tata Udara', 'program_keahlian' => 'Teknik Pendinginan dan Tata Udara', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['R', 'I']],
            ['code' => 'BUS', 'name' => 'Busana', 'program_keahlian' => 'Desain dan Produksi Busana', 'bidang_keahlian' => 'Seni dan Ekonomi Kreatif', 'riasec_profile' => ['R', 'E']],

            // ======= INVESTIGATIVE (additional) =======
            ['code' => 'DKV', 'name' => 'Desain Komunikasi Visual', 'program_keahlian' => 'Desain Komunikasi Visual', 'bidang_keahlian' => 'Seni dan Ekonomi Kreatif', 'riasec_profile' => ['I', 'A']],
            ['code' => 'APL', 'name' => 'Analisis Penguji Laboratorium', 'program_keahlian' => 'Kimia Analisis', 'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa', 'riasec_profile' => ['I', 'S']],
            ['code' => 'DPIB', 'name' => 'Desain Permodelan dan Informasi Bangunan', 'program_keahlian' => 'Teknik Konstruksi dan Perumahan', 'bidang_keahlian' => 'Teknologi Konstruksi', 'riasec_profile' => ['I', 'A']],
            ['code' => 'DPIB2', 'name' => 'Desain Permodelan Ilmu Bangunan', 'program_keahlian' => 'Teknik Konstruksi dan Perumahan', 'bidang_keahlian' => 'Teknologi Konstruksi', 'riasec_profile' => ['I', 'A']],

            // ======= ARTISTIC (additional) =======
            ['code' => 'BDP', 'name' => 'Bisnis Daring dan Pemasaran', 'program_keahlian' => 'Pemasaran', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['A', 'E', 'S']],
            ['code' => 'PMS', 'name' => 'Pemasaran', 'program_keahlian' => 'Pemasaran', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['A', 'E', 'S']],
            ['code' => 'BD', 'name' => 'Bisnis Digital', 'program_keahlian' => 'Pemasaran', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['A']],
            ['code' => 'ANI', 'name' => 'Animasi', 'program_keahlian' => 'Animasi', 'bidang_keahlian' => 'Seni dan Ekonomi Kreatif', 'riasec_profile' => ['A']],
            ['code' => 'TAV', 'name' => 'Teknik Audio-Video', 'program_keahlian' => 'Broadcasting dan Perfilman', 'bidang_keahlian' => 'Seni dan Ekonomi Kreatif', 'riasec_profile' => ['A']],
            ['code' => 'DPB', 'name' => 'Desain Produksi Busana', 'program_keahlian' => 'Desain dan Produksi Busana', 'bidang_keahlian' => 'Seni dan Ekonomi Kreatif', 'riasec_profile' => ['A', 'S', 'E']],

            // ======= SOCIAL (additional) =======
            ['code' => 'ULP', 'name' => 'Usaha Layanan Pariwisata', 'program_keahlian' => 'Usaha Layanan Pariwisata', 'bidang_keahlian' => 'Pariwisata', 'riasec_profile' => ['S', 'E', 'C']],
            ['code' => 'PHT', 'name' => 'Perhotelan', 'program_keahlian' => 'Perhotelan', 'bidang_keahlian' => 'Pariwisata', 'riasec_profile' => ['S', 'E', 'C']],
            ['code' => 'MPLB', 'name' => 'Manajemen Perkantoran dan Layanan Bisnis', 'program_keahlian' => 'Manajemen Perkantoran dan Layanan Bisnis', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['S', 'E', 'C']],

            // ======= ENTERPRISING (additional) =======
            ['code' => 'AKL', 'name' => 'Akuntansi dan Keuangan Lembaga', 'program_keahlian' => 'Akuntansi dan Keuangan Lembaga', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['E', 'C']],
            ['code' => 'OTKP', 'name' => 'Otomatisasi dan Tata Kelola Perkantoran', 'program_keahlian' => 'Manajemen Perkantoran dan Layanan Bisnis', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['E', 'C']],
            ['code' => 'ML', 'name' => 'Manajemen Logistik', 'program_keahlian' => 'Logistik', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['E', 'C']],

            // ======= CONVENTIONAL (additional) =======
            ['code' => 'MP', 'name' => 'Manajemen Perkantoran', 'program_keahlian' => 'Manajemen Perkantoran dan Layanan Bisnis', 'bidang_keahlian' => 'Bisnis dan Manajemen', 'riasec_profile' => ['C']],
        ];

        foreach ($majors as $major) {
            SmkMajor::create([
                'code' => $major['code'],
                'name' => $major['name'],
                'program_keahlian' => $major['program_keahlian'],
                'bidang_keahlian' => $major['bidang_keahlian'],
                'description' => null,
                'career_prospects' => null,
                'skills_learned' => null,
                'riasec_profile' => $major['riasec_profile'],
                'is_active' => true,
            ]);
        }
    }
}
