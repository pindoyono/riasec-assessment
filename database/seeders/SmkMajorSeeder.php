<?php

namespace Database\Seeders;

use App\Models\SmkMajor;
use Illuminate\Database\Seeder;

class SmkMajorSeeder extends Seeder
{
    /**
     * Seeder berdasarkan Spektrum Keahlian SMK Kurikulum Merdeka 2024
     * Sumber: Keputusan Kepala BSKAP Kemendikbudristek
     */
    public function run(): void
    {
        $majors = [
            // ================================================================
            // BIDANG KEAHLIAN: TEKNOLOGI MANUFAKTUR DAN REKAYASA
            // ================================================================

            // Program Keahlian: Teknik Konstruksi dan Perumahan
            [
                'code' => 'DPIB',
                'name' => 'Desain Pemodelan dan Informasi Bangunan',
                'program_keahlian' => 'Teknik Konstruksi dan Perumahan',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari perancangan dan pemodelan bangunan menggunakan teknologi BIM (Building Information Modeling).',
                'career_prospects' => 'Drafter, estimator, BIM modeler, konsultan konstruksi, pengawas proyek',
                'skills_learned' => 'AutoCAD, Revit, SketchUp, perhitungan RAB, gambar teknik',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'KGSP',
                'name' => 'Konstruksi Gedung, Sanitasi, dan Perawatan',
                'program_keahlian' => 'Teknik Konstruksi dan Perumahan',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari teknik pembangunan gedung, sistem sanitasi, dan perawatan bangunan.',
                'career_prospects' => 'Teknisi bangunan, supervisor proyek, kontraktor, inspektur bangunan',
                'skills_learned' => 'Konstruksi bangunan, instalasi sanitasi, perawatan gedung, K3 konstruksi',
                'riasec_profile' => ['R', 'C', 'I'],
            ],
            [
                'code' => 'TJSB',
                'name' => 'Teknik Jalan, Irigasi, dan Jembatan',
                'program_keahlian' => 'Teknik Konstruksi dan Perumahan',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari pembangunan infrastruktur jalan, sistem irigasi, dan jembatan.',
                'career_prospects' => 'Teknisi jalan, surveyor, pengawas irigasi, kontraktor infrastruktur',
                'skills_learned' => 'Pengukuran tanah, konstruksi jalan, sistem irigasi, jembatan',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TF',
                'name' => 'Teknik Furnitur',
                'program_keahlian' => 'Teknik Konstruksi dan Perumahan',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari desain dan pembuatan furnitur kayu dan material modern.',
                'career_prospects' => 'Desainer furnitur, tukang kayu profesional, wirausaha mebel',
                'skills_learned' => 'Desain furnitur, pengerjaan kayu, finishing, mesin woodworking',
                'riasec_profile' => ['R', 'A', 'I'],
            ],

            // Program Keahlian: Teknik Mesin
            [
                'code' => 'TPM',
                'name' => 'Teknik Pemesinan',
                'program_keahlian' => 'Teknik Mesin',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari pengoperasian mesin perkakas untuk pembuatan komponen logam.',
                'career_prospects' => 'Operator mesin CNC, teknisi mesin, quality control manufaktur, machinist',
                'skills_learned' => 'Bubut, frais, CNC programming, gambar teknik, metrologi',
                'riasec_profile' => ['R', 'C', 'I'],
            ],
            [
                'code' => 'TPL',
                'name' => 'Teknik Pengelasan',
                'program_keahlian' => 'Teknik Mesin',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari berbagai teknik pengelasan logam dan fabrikasi.',
                'career_prospects' => 'Welder bersertifikat, welding inspector, fabricator, pipe fitter',
                'skills_learned' => 'SMAW, GMAW, GTAW, fabrikasi logam, inspeksi las',
                'riasec_profile' => ['R', 'C', 'I'],
            ],
            [
                'code' => 'TPFL',
                'name' => 'Teknik Pengecoran dan Fabrikasi Logam',
                'program_keahlian' => 'Teknik Mesin',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari teknik pengecoran logam dan fabrikasi komponen industri.',
                'career_prospects' => 'Teknisi foundry, operator cor logam, quality control casting',
                'skills_learned' => 'Pengecoran logam, pattern making, fabrikasi, metalurgi dasar',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Teknik Otomotif
            [
                'code' => 'TKRO',
                'name' => 'Teknik Kendaraan Ringan Otomotif',
                'program_keahlian' => 'Teknik Otomotif',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari perawatan dan perbaikan kendaraan ringan seperti mobil penumpang.',
                'career_prospects' => 'Mekanik mobil, teknisi dealer, service advisor, wirausaha bengkel',
                'skills_learned' => 'Engine tune-up, sistem kelistrikan, chasis, pemindah tenaga, AC mobil',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TBSM',
                'name' => 'Teknik dan Bisnis Sepeda Motor',
                'program_keahlian' => 'Teknik Otomotif',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari perawatan, perbaikan, dan bisnis sepeda motor.',
                'career_prospects' => 'Mekanik motor, teknisi dealer resmi, wirausaha bengkel motor',
                'skills_learned' => 'Perawatan motor, sistem injeksi, kelistrikan motor, manajemen bengkel',
                'riasec_profile' => ['R', 'E', 'C'],
            ],
            [
                'code' => 'TOAB',
                'name' => 'Teknik Otomotif Alat Berat',
                'program_keahlian' => 'Teknik Otomotif',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari perawatan dan perbaikan alat berat konstruksi dan pertambangan.',
                'career_prospects' => 'Mekanik alat berat, teknisi heavy equipment, foreman maintenance',
                'skills_learned' => 'Sistem hidrolik, engine diesel, undercarriage, electrical system',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TBO',
                'name' => 'Teknik Bodi Otomotif',
                'program_keahlian' => 'Teknik Otomotif',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari perbaikan dan pengecatan bodi kendaraan.',
                'career_prospects' => 'Teknisi body repair, spray painter, estimator kerusakan',
                'skills_learned' => 'Body repair, painting, welding bodi, detailing',
                'riasec_profile' => ['R', 'A', 'C'],
            ],
            [
                'code' => 'TKEV',
                'name' => 'Teknik Kendaraan Listrik',
                'program_keahlian' => 'Teknik Otomotif',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari teknologi dan perawatan kendaraan listrik (EV).',
                'career_prospects' => 'Teknisi EV, engineer charging station, teknisi baterai',
                'skills_learned' => 'Motor listrik, sistem baterai, charging system, BMS',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Teknik Ketenagalistrikan
            [
                'code' => 'TITL',
                'name' => 'Teknik Instalasi Tenaga Listrik',
                'program_keahlian' => 'Teknik Ketenagalistrikan',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari pemasangan dan pemeliharaan instalasi listrik.',
                'career_prospects' => 'Teknisi listrik, kontraktor listrik, teknisi PLN, electrical engineer',
                'skills_learned' => 'Instalasi listrik, panel listrik, PLC, motor control',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TPTL',
                'name' => 'Teknik Pembangkit Tenaga Listrik',
                'program_keahlian' => 'Teknik Ketenagalistrikan',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari pengoperasian dan pemeliharaan pembangkit listrik.',
                'career_prospects' => 'Operator pembangkit, teknisi power plant, maintenance engineer',
                'skills_learned' => 'Turbin, generator, sistem kontrol pembangkit, K3 kelistrikan',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TJTL',
                'name' => 'Teknik Jaringan Tenaga Listrik',
                'program_keahlian' => 'Teknik Ketenagalistrikan',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari instalasi dan pemeliharaan jaringan distribusi listrik.',
                'career_prospects' => 'Teknisi jaringan PLN, line man, teknisi gardu',
                'skills_learned' => 'Jaringan distribusi, trafo, proteksi sistem, K3 jaringan listrik',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Teknik Elektronika
            [
                'code' => 'TAV',
                'name' => 'Teknik Audio Video',
                'program_keahlian' => 'Teknik Elektronika',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari sistem audio video dan elektronika konsumen.',
                'career_prospects' => 'Teknisi elektronik, sound engineer, teknisi broadcast',
                'skills_learned' => 'Elektronika dasar, audio system, video editing, troubleshooting',
                'riasec_profile' => ['R', 'I', 'A'],
            ],
            [
                'code' => 'TEI',
                'name' => 'Teknik Elektronika Industri',
                'program_keahlian' => 'Teknik Elektronika',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari sistem elektronika untuk keperluan industri.',
                'career_prospects' => 'Teknisi instrumentasi, automation engineer, maintenance elektronik',
                'skills_learned' => 'Elektronika industri, sensor, PLC, SCADA, pneumatik',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TM',
                'name' => 'Teknik Mekatronika',
                'program_keahlian' => 'Teknik Elektronika',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari integrasi sistem mekanik, elektronik, dan kontrol.',
                'career_prospects' => 'Teknisi mekatronika, robotics engineer, automation specialist',
                'skills_learned' => 'Robotika, PLC, pneumatik, hidrolik, sistem kontrol',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Teknik Grafika
            [
                'code' => 'DG',
                'name' => 'Desain Grafika',
                'program_keahlian' => 'Teknik Grafika',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari desain untuk industri percetakan dan kemasan.',
                'career_prospects' => 'Desainer grafis, prepress operator, packaging designer',
                'skills_learned' => 'CorelDraw, Adobe Illustrator, desain kemasan, prepress',
                'riasec_profile' => ['A', 'R', 'I'],
            ],
            [
                'code' => 'PG',
                'name' => 'Produksi Grafika',
                'program_keahlian' => 'Teknik Grafika',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari proses produksi cetak dan finishing.',
                'career_prospects' => 'Operator mesin cetak, supervisor produksi, quality control',
                'skills_learned' => 'Offset printing, digital printing, finishing, color management',
                'riasec_profile' => ['R', 'C', 'I'],
            ],

            // Program Keahlian: Teknik Pendinginan dan Tata Udara
            [
                'code' => 'TPTU',
                'name' => 'Teknik Pendinginan dan Tata Udara',
                'program_keahlian' => 'Teknik Pendinginan dan Tata Udara',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari sistem pendinginan, AC, dan refrigerasi.',
                'career_prospects' => 'Teknisi AC, teknisi chiller, maintenance HVAC',
                'skills_learned' => 'Refrigerasi, AC split, chiller, cold storage, troubleshooting',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Teknik Kimia
            [
                'code' => 'KI',
                'name' => 'Kimia Industri',
                'program_keahlian' => 'Teknik Kimia',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari proses kimia dalam industri manufaktur.',
                'career_prospects' => 'Operator proses, quality control, lab analyst industri',
                'skills_learned' => 'Proses kimia, analisis laboratorium, K3 kimia, quality control',
                'riasec_profile' => ['I', 'R', 'C'],
            ],
            [
                'code' => 'KA',
                'name' => 'Kimia Analisis',
                'program_keahlian' => 'Teknik Kimia',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari teknik analisis kimia di laboratorium.',
                'career_prospects' => 'Lab analyst, quality control, research assistant',
                'skills_learned' => 'Analisis kualitatif/kuantitatif, instrumen lab, sampling',
                'riasec_profile' => ['I', 'C', 'R'],
            ],

            // Program Keahlian: Teknik Tekstil
            [
                'code' => 'TPPT',
                'name' => 'Teknik Pemintalan dan Pembuatan Tali',
                'program_keahlian' => 'Teknik Tekstil',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari proses pemintalan serat menjadi benang dan tali.',
                'career_prospects' => 'Operator spinning, supervisor produksi tekstil',
                'skills_learned' => 'Mesin spinning, quality control benang, maintenance mesin',
                'riasec_profile' => ['R', 'C', 'I'],
            ],
            [
                'code' => 'TPT',
                'name' => 'Teknik Pertenunan',
                'program_keahlian' => 'Teknik Tekstil',
                'bidang_keahlian' => 'Teknologi Manufaktur dan Rekayasa',
                'description' => 'Mempelajari proses pembuatan kain dengan teknik tenun.',
                'career_prospects' => 'Operator weaving, teknisi mesin tenun, QC tekstil',
                'skills_learned' => 'Mesin tenun, desain kain, pola tenun, quality control',
                'riasec_profile' => ['R', 'A', 'C'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: ENERGI DAN PERTAMBANGAN
            // ================================================================

            // Program Keahlian: Teknik Energi Terbarukan
            [
                'code' => 'TESHA',
                'name' => 'Teknik Energi Surya, Hidro, dan Angin',
                'program_keahlian' => 'Teknik Energi Terbarukan',
                'bidang_keahlian' => 'Energi dan Pertambangan',
                'description' => 'Mempelajari teknologi pembangkit energi terbarukan.',
                'career_prospects' => 'Teknisi solar panel, teknisi PLTA, teknisi wind turbine',
                'skills_learned' => 'Instalasi solar PV, turbin air, turbin angin, sistem inverter',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TEB',
                'name' => 'Teknik Energi Biomassa',
                'program_keahlian' => 'Teknik Energi Terbarukan',
                'bidang_keahlian' => 'Energi dan Pertambangan',
                'description' => 'Mempelajari konversi biomassa menjadi energi.',
                'career_prospects' => 'Operator biogas, teknisi biomass plant, konsultan energi hijau',
                'skills_learned' => 'Produksi biogas, biomass processing, waste to energy',
                'riasec_profile' => ['R', 'I', 'E'],
            ],

            // Program Keahlian: Geologi Pertambangan
            [
                'code' => 'TGP',
                'name' => 'Teknik Geologi Pertambangan',
                'program_keahlian' => 'Geologi Pertambangan',
                'bidang_keahlian' => 'Energi dan Pertambangan',
                'description' => 'Mempelajari eksplorasi dan geologi untuk pertambangan.',
                'career_prospects' => 'Surveyor tambang, geological technician, mine planning assistant',
                'skills_learned' => 'Pemetaan geologi, sampling, core logging, software geologi',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TP',
                'name' => 'Teknik Pertambangan',
                'program_keahlian' => 'Geologi Pertambangan',
                'bidang_keahlian' => 'Energi dan Pertambangan',
                'description' => 'Mempelajari teknik penambangan dan pengolahan mineral.',
                'career_prospects' => 'Operator tambang, supervisor produksi, mine surveyor',
                'skills_learned' => 'Teknik penambangan, K3 tambang, alat berat, pengolahan mineral',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Teknik Perminyakan
            [
                'code' => 'TPBG',
                'name' => 'Teknik Produksi Minyak dan Gas',
                'program_keahlian' => 'Teknik Perminyakan',
                'bidang_keahlian' => 'Energi dan Pertambangan',
                'description' => 'Mempelajari proses produksi minyak dan gas bumi.',
                'career_prospects' => 'Operator produksi migas, teknisi well services, field operator',
                'skills_learned' => 'Produksi migas, well testing, artificial lift, facility operation',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TPB',
                'name' => 'Teknik Pemboran Minyak dan Gas',
                'program_keahlian' => 'Teknik Perminyakan',
                'bidang_keahlian' => 'Energi dan Pertambangan',
                'description' => 'Mempelajari teknik pemboran sumur minyak dan gas.',
                'career_prospects' => 'Drilling crew, roughneck, floorman, derrickman',
                'skills_learned' => 'Drilling operation, well control, rig equipment, HSE migas',
                'riasec_profile' => ['R', 'C', 'I'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: TEKNOLOGI INFORMASI
            // ================================================================

            // Program Keahlian: Teknik Jaringan Komputer dan Telekomunikasi
            [
                'code' => 'TJKT',
                'name' => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'program_keahlian' => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'bidang_keahlian' => 'Teknologi Informasi',
                'description' => 'Mempelajari instalasi dan pemeliharaan jaringan komputer dan sistem telekomunikasi.',
                'career_prospects' => 'Network administrator, IT support, NOC engineer, system administrator',
                'skills_learned' => 'Jaringan komputer, server, keamanan jaringan, cloud computing, Cisco/Mikrotik',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Pengembangan Perangkat Lunak dan Gim
            [
                'code' => 'PPLG',
                'name' => 'Pengembangan Perangkat Lunak dan Gim',
                'program_keahlian' => 'Pengembangan Perangkat Lunak dan Gim',
                'bidang_keahlian' => 'Teknologi Informasi',
                'description' => 'Mempelajari pemrograman, pengembangan aplikasi, dan game development.',
                'career_prospects' => 'Software developer, web developer, mobile developer, game developer',
                'skills_learned' => 'Pemrograman, database, UI/UX, game engine, web framework, mobile development',
                'riasec_profile' => ['I', 'R', 'A'],
            ],

            // Program Keahlian: Desain Komunikasi Visual
            [
                'code' => 'DKV',
                'name' => 'Desain Komunikasi Visual',
                'program_keahlian' => 'Desain Komunikasi Visual',
                'bidang_keahlian' => 'Teknologi Informasi',
                'description' => 'Mempelajari desain grafis, branding, dan komunikasi visual.',
                'career_prospects' => 'Graphic designer, UI/UX designer, art director, brand designer',
                'skills_learned' => 'Adobe Creative Suite, desain grafis, tipografi, branding, layout',
                'riasec_profile' => ['A', 'I', 'E'],
            ],

            // Program Keahlian: Animasi
            [
                'code' => 'ANI',
                'name' => 'Animasi',
                'program_keahlian' => 'Animasi',
                'bidang_keahlian' => 'Teknologi Informasi',
                'description' => 'Mempelajari pembuatan animasi 2D, 3D, dan motion graphics.',
                'career_prospects' => 'Animator 2D/3D, motion graphic designer, VFX artist, rigger',
                'skills_learned' => 'Animasi 2D/3D, storyboarding, character design, rigging, Blender/Maya',
                'riasec_profile' => ['A', 'I', 'R'],
            ],

            // Program Keahlian: Broadcasting dan Perfilman
            [
                'code' => 'BC',
                'name' => 'Broadcasting dan Perfilman',
                'program_keahlian' => 'Broadcasting dan Perfilman',
                'bidang_keahlian' => 'Teknologi Informasi',
                'description' => 'Mempelajari produksi konten audio visual, broadcasting, dan film.',
                'career_prospects' => 'Videographer, video editor, content creator, cinematographer, producer',
                'skills_learned' => 'Sinematografi, video editing, sound design, directing, live streaming',
                'riasec_profile' => ['A', 'E', 'S'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: KESEHATAN DAN PEKERJAAN SOSIAL
            // ================================================================

            // Program Keahlian: Keperawatan
            [
                'code' => 'KPR',
                'name' => 'Keperawatan',
                'program_keahlian' => 'Keperawatan',
                'bidang_keahlian' => 'Kesehatan dan Pekerjaan Sosial',
                'description' => 'Mempelajari dasar-dasar keperawatan dan pelayanan kesehatan.',
                'career_prospects' => 'Asisten perawat, caregiver, perawat lansia, tenaga kesehatan',
                'skills_learned' => 'Keperawatan dasar, pertolongan pertama, perawatan pasien, anatomi',
                'riasec_profile' => ['S', 'I', 'C'],
            ],

            // Program Keahlian: Teknologi Laboratorium Medik
            [
                'code' => 'TLM',
                'name' => 'Teknologi Laboratorium Medik',
                'program_keahlian' => 'Teknologi Laboratorium Medik',
                'bidang_keahlian' => 'Kesehatan dan Pekerjaan Sosial',
                'description' => 'Mempelajari pemeriksaan laboratorium untuk diagnostik kesehatan.',
                'career_prospects' => 'Teknisi laboratorium, analis kesehatan, phlebotomist',
                'skills_learned' => 'Hematologi, mikrobiologi, kimia klinik, sampling darah',
                'riasec_profile' => ['I', 'R', 'C'],
            ],

            // Program Keahlian: Farmasi
            [
                'code' => 'FKK',
                'name' => 'Farmasi Klinis dan Komunitas',
                'program_keahlian' => 'Farmasi',
                'bidang_keahlian' => 'Kesehatan dan Pekerjaan Sosial',
                'description' => 'Mempelajari kefarmasian, dispensing obat, dan pelayanan farmasi.',
                'career_prospects' => 'Asisten apoteker, TTK (Tenaga Teknis Kefarmasian), industri farmasi',
                'skills_learned' => 'Farmakologi, dispensing, compounding, pelayanan farmasi',
                'riasec_profile' => ['I', 'S', 'C'],
            ],
            [
                'code' => 'FI',
                'name' => 'Farmasi Industri',
                'program_keahlian' => 'Farmasi',
                'bidang_keahlian' => 'Kesehatan dan Pekerjaan Sosial',
                'description' => 'Mempelajari produksi dan quality control obat di industri farmasi.',
                'career_prospects' => 'Operator produksi farmasi, quality control, quality assurance',
                'skills_learned' => 'Produksi obat, CPOB, quality control, formulasi',
                'riasec_profile' => ['I', 'C', 'R'],
            ],

            // Program Keahlian: Dental Asisten
            [
                'code' => 'DA',
                'name' => 'Dental Asisten',
                'program_keahlian' => 'Dental Asisten',
                'bidang_keahlian' => 'Kesehatan dan Pekerjaan Sosial',
                'description' => 'Mempelajari asistensi dokter gigi dan perawatan gigi dasar.',
                'career_prospects' => 'Dental assistant, dental hygienist assistant, klinik gigi',
                'skills_learned' => 'Asistensi dental, sterilisasi, dental record, patient handling',
                'riasec_profile' => ['S', 'C', 'I'],
            ],

            // Program Keahlian: Pekerjaan Sosial
            [
                'code' => 'PS',
                'name' => 'Pekerjaan Sosial',
                'program_keahlian' => 'Pekerjaan Sosial',
                'bidang_keahlian' => 'Kesehatan dan Pekerjaan Sosial',
                'description' => 'Mempelajari pelayanan sosial dan pemberdayaan masyarakat.',
                'career_prospects' => 'Pekerja sosial, pendamping PKH, community worker, NGO staff',
                'skills_learned' => 'Konseling dasar, community development, case management',
                'riasec_profile' => ['S', 'E', 'A'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: AGRIBISNIS DAN AGROTEKNOLOGI
            // ================================================================

            // Program Keahlian: Agribisnis Tanaman
            [
                'code' => 'ATPH',
                'name' => 'Agribisnis Tanaman Pangan dan Hortikultura',
                'program_keahlian' => 'Agribisnis Tanaman',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari budidaya tanaman pangan dan hortikultura serta agribisnisnya.',
                'career_prospects' => 'Petani modern, agronomist, konsultan pertanian, agribusiness',
                'skills_learned' => 'Budidaya tanaman, pemupukan, pengendalian hama, greenhouse',
                'riasec_profile' => ['R', 'I', 'E'],
            ],
            [
                'code' => 'ATBUN',
                'name' => 'Agribisnis Tanaman Perkebunan',
                'program_keahlian' => 'Agribisnis Tanaman',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari budidaya dan manajemen tanaman perkebunan.',
                'career_prospects' => 'Asisten kebun, mandor, field supervisor, estate manager',
                'skills_learned' => 'Budidaya kelapa sawit, karet, kopi, manajemen perkebunan',
                'riasec_profile' => ['R', 'E', 'I'],
            ],
            [
                'code' => 'LPT',
                'name' => 'Lanskap dan Pertamanan',
                'program_keahlian' => 'Agribisnis Tanaman',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari desain dan pemeliharaan taman dan lanskap.',
                'career_prospects' => 'Landscape designer, gardener profesional, green building',
                'skills_learned' => 'Desain taman, hardscape, softscape, irigasi taman',
                'riasec_profile' => ['A', 'R', 'E'],
            ],

            // Program Keahlian: Agribisnis Ternak
            [
                'code' => 'ATU',
                'name' => 'Agribisnis Ternak Unggas',
                'program_keahlian' => 'Agribisnis Ternak',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari budidaya dan bisnis ternak unggas (ayam, bebek, dll).',
                'career_prospects' => 'Peternak unggas, supervisor farm, technical service poultry',
                'skills_learned' => 'Manajemen unggas, nutrisi pakan, biosecurity, recording',
                'riasec_profile' => ['R', 'E', 'I'],
            ],
            [
                'code' => 'ATR',
                'name' => 'Agribisnis Ternak Ruminansia',
                'program_keahlian' => 'Agribisnis Ternak',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari budidaya dan bisnis ternak ruminansia (sapi, kambing, dll).',
                'career_prospects' => 'Peternak sapi/kambing, inseminator, supervisor feedlot',
                'skills_learned' => 'Manajemen ruminansia, pakan ternak, reproduksi, penggemukan',
                'riasec_profile' => ['R', 'I', 'E'],
            ],

            // Program Keahlian: Agribisnis Pengolahan Hasil Pertanian
            [
                'code' => 'APHP',
                'name' => 'Agribisnis Pengolahan Hasil Pertanian',
                'program_keahlian' => 'Agribisnis Pengolahan Hasil Pertanian',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari pengolahan hasil pertanian menjadi produk bernilai tambah.',
                'career_prospects' => 'Quality control pangan, supervisor produksi, food technologist',
                'skills_learned' => 'Pengolahan pangan, pengemasan, quality control, HACCP',
                'riasec_profile' => ['R', 'I', 'E'],
            ],

            // Program Keahlian: Kehutanan
            [
                'code' => 'TPHP',
                'name' => 'Teknik Produksi Hasil Hutan',
                'program_keahlian' => 'Kehutanan',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari teknik pengelolaan dan produksi hasil hutan.',
                'career_prospects' => 'Teknisi kehutanan, supervisor HPH, forest ranger',
                'skills_learned' => 'Inventarisasi hutan, penebangan, konservasi, GIS kehutanan',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TKH',
                'name' => 'Teknik Konservasi Hutan',
                'program_keahlian' => 'Kehutanan',
                'bidang_keahlian' => 'Agribisnis dan Agroteknologi',
                'description' => 'Mempelajari konservasi hutan dan rehabilitasi lahan.',
                'career_prospects' => 'Polisi hutan, petugas konservasi, staff KLHK, NGO lingkungan',
                'skills_learned' => 'Konservasi hutan, rehabilitasi lahan, perlindungan satwa',
                'riasec_profile' => ['R', 'I', 'S'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: KEMARITIMAN
            // ================================================================

            // Program Keahlian: Pelayaran Kapal Niaga
            [
                'code' => 'NKN',
                'name' => 'Nautika Kapal Niaga',
                'program_keahlian' => 'Pelayaran Kapal Niaga',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari navigasi dan operasional kapal niaga.',
                'career_prospects' => 'Perwira navigasi, mualim, nahkoda, officer kapal',
                'skills_learned' => 'Navigasi, GMDSS, cargo handling, keselamatan pelayaran',
                'riasec_profile' => ['R', 'I', 'C'],
            ],
            [
                'code' => 'TKPN',
                'name' => 'Teknika Kapal Niaga',
                'program_keahlian' => 'Pelayaran Kapal Niaga',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari mesin kapal dan sistem teknis kapal niaga.',
                'career_prospects' => 'Masinis kapal, engineer kapal, chief engineer',
                'skills_learned' => 'Mesin diesel marine, sistem propulsi, kelistrikan kapal',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Pelayaran Kapal Penangkap Ikan
            [
                'code' => 'NKPI',
                'name' => 'Nautika Kapal Penangkap Ikan',
                'program_keahlian' => 'Pelayaran Kapal Penangkap Ikan',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari navigasi dan teknik penangkapan ikan di laut.',
                'career_prospects' => 'Nahkoda kapal ikan, fishing master, observer perikanan',
                'skills_learned' => 'Navigasi, teknik penangkapan ikan, fish finding, keselamatan',
                'riasec_profile' => ['R', 'E', 'I'],
            ],
            [
                'code' => 'TKPI',
                'name' => 'Teknika Kapal Penangkap Ikan',
                'program_keahlian' => 'Pelayaran Kapal Penangkap Ikan',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari mesin dan peralatan kapal penangkap ikan.',
                'career_prospects' => 'Masinis kapal ikan, teknisi mesin kapal ikan',
                'skills_learned' => 'Mesin kapal ikan, refrigerasi hasil tangkapan, perawatan mesin',
                'riasec_profile' => ['R', 'I', 'C'],
            ],

            // Program Keahlian: Agribisnis Perikanan
            [
                'code' => 'APL',
                'name' => 'Agribisnis Perikanan Air Laut',
                'program_keahlian' => 'Agribisnis Perikanan',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari budidaya dan bisnis perikanan laut.',
                'career_prospects' => 'Teknisi budidaya laut, supervisor hatchery, konsultan perikanan',
                'skills_learned' => 'Budidaya ikan laut, hatchery, manajemen tambak',
                'riasec_profile' => ['R', 'E', 'I'],
            ],
            [
                'code' => 'APT',
                'name' => 'Agribisnis Perikanan Air Tawar',
                'program_keahlian' => 'Agribisnis Perikanan',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari budidaya dan bisnis perikanan air tawar.',
                'career_prospects' => 'Pembudidaya ikan, teknisi pembenihan, wirausaha perikanan',
                'skills_learned' => 'Budidaya ikan tawar, pembenihan, kolam terpal/beton',
                'riasec_profile' => ['R', 'E', 'I'],
            ],
            [
                'code' => 'APRL',
                'name' => 'Agribisnis Rumput Laut',
                'program_keahlian' => 'Agribisnis Perikanan',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari budidaya dan pengolahan rumput laut.',
                'career_prospects' => 'Petani rumput laut, pengolah rumput laut, eksportir',
                'skills_learned' => 'Budidaya rumput laut, pengolahan pasca panen, pemasaran',
                'riasec_profile' => ['R', 'E', 'I'],
            ],
            [
                'code' => 'PPHP',
                'name' => 'Pengolahan dan Pemasaran Hasil Perikanan',
                'program_keahlian' => 'Agribisnis Perikanan',
                'bidang_keahlian' => 'Kemaritiman',
                'description' => 'Mempelajari pengolahan dan pemasaran produk perikanan.',
                'career_prospects' => 'Quality control seafood, supervisor pengolahan, marketing',
                'skills_learned' => 'Pengolahan ikan, cold chain, quality control, pemasaran',
                'riasec_profile' => ['R', 'E', 'C'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: BISNIS DAN MANAJEMEN
            // ================================================================

            // Program Keahlian: Akuntansi dan Keuangan
            [
                'code' => 'AKL',
                'name' => 'Akuntansi dan Keuangan Lembaga',
                'program_keahlian' => 'Akuntansi dan Keuangan',
                'bidang_keahlian' => 'Bisnis dan Manajemen',
                'description' => 'Mempelajari pembukuan, akuntansi, dan keuangan lembaga.',
                'career_prospects' => 'Staff akuntansi, kasir, admin keuangan, tax officer',
                'skills_learned' => 'Akuntansi, perpajakan, aplikasi akuntansi (MYOB, Accurate)',
                'riasec_profile' => ['C', 'E', 'I'],
            ],
            [
                'code' => 'PB',
                'name' => 'Perbankan dan Keuangan Mikro',
                'program_keahlian' => 'Akuntansi dan Keuangan',
                'bidang_keahlian' => 'Bisnis dan Manajemen',
                'description' => 'Mempelajari operasional perbankan dan lembaga keuangan mikro.',
                'career_prospects' => 'Teller bank, customer service, analis kredit, koperasi',
                'skills_learned' => 'Operasional bank, analisis kredit, layanan nasabah',
                'riasec_profile' => ['C', 'E', 'S'],
            ],

            // Program Keahlian: Manajemen Perkantoran
            [
                'code' => 'MPLB',
                'name' => 'Manajemen Perkantoran dan Layanan Bisnis',
                'program_keahlian' => 'Manajemen Perkantoran',
                'bidang_keahlian' => 'Bisnis dan Manajemen',
                'description' => 'Mempelajari administrasi perkantoran dan layanan bisnis modern.',
                'career_prospects' => 'Admin kantor, sekretaris, office manager, personal assistant',
                'skills_learned' => 'Administrasi, korespondensi, kearsipan digital, Ms. Office',
                'riasec_profile' => ['C', 'S', 'E'],
            ],

            // Program Keahlian: Bisnis dan Pemasaran
            [
                'code' => 'BDP',
                'name' => 'Bisnis Digital',
                'program_keahlian' => 'Bisnis dan Pemasaran',
                'bidang_keahlian' => 'Bisnis dan Manajemen',
                'description' => 'Mempelajari bisnis online dan pemasaran digital.',
                'career_prospects' => 'Digital marketer, e-commerce specialist, social media specialist',
                'skills_learned' => 'Digital marketing, SEO/SEM, marketplace, content marketing',
                'riasec_profile' => ['E', 'I', 'A'],
            ],
            [
                'code' => 'PM',
                'name' => 'Pemasaran',
                'program_keahlian' => 'Bisnis dan Pemasaran',
                'bidang_keahlian' => 'Bisnis dan Manajemen',
                'description' => 'Mempelajari strategi pemasaran dan penjualan.',
                'career_prospects' => 'Sales executive, marketing staff, merchandiser, brand ambassador',
                'skills_learned' => 'Pemasaran, negosiasi, customer relation, visual merchandising',
                'riasec_profile' => ['E', 'S', 'C'],
            ],
            [
                'code' => 'BDPR',
                'name' => 'Bisnis Daring dan Pemasaran Ritel',
                'program_keahlian' => 'Bisnis dan Pemasaran',
                'bidang_keahlian' => 'Bisnis dan Manajemen',
                'description' => 'Mempelajari bisnis ritel modern dan marketplace.',
                'career_prospects' => 'Store supervisor, kasir, visual merchandiser, marketplace seller',
                'skills_learned' => 'Retail management, POS system, inventory, customer service',
                'riasec_profile' => ['E', 'C', 'S'],
            ],

            // Program Keahlian: Logistik
            [
                'code' => 'MLG',
                'name' => 'Manajemen Logistik',
                'program_keahlian' => 'Logistik',
                'bidang_keahlian' => 'Bisnis dan Manajemen',
                'description' => 'Mempelajari manajemen rantai pasok dan logistik.',
                'career_prospects' => 'Staff logistik, warehouse supervisor, supply chain planner',
                'skills_learned' => 'Supply chain, warehouse management, inventory control',
                'riasec_profile' => ['C', 'E', 'R'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: PARIWISATA
            // ================================================================

            // Program Keahlian: Perhotelan dan Jasa Pariwisata
            [
                'code' => 'PH',
                'name' => 'Perhotelan',
                'program_keahlian' => 'Perhotelan dan Jasa Pariwisata',
                'bidang_keahlian' => 'Pariwisata',
                'description' => 'Mempelajari manajemen hotel dan pelayanan tamu.',
                'career_prospects' => 'Front office, housekeeping, F&B service, guest relation',
                'skills_learned' => 'Hospitality, front office, housekeeping, F&B service, bahasa asing',
                'riasec_profile' => ['S', 'E', 'C'],
            ],
            [
                'code' => 'UPW',
                'name' => 'Usaha Layanan Pariwisata',
                'program_keahlian' => 'Perhotelan dan Jasa Pariwisata',
                'bidang_keahlian' => 'Pariwisata',
                'description' => 'Mempelajari pengelolaan perjalanan wisata dan event.',
                'career_prospects' => 'Tour guide, travel consultant, ticketing staff, event organizer',
                'skills_learned' => 'Tour planning, ticketing, event management, bahasa asing',
                'riasec_profile' => ['E', 'S', 'A'],
            ],
            [
                'code' => 'MICE',
                'name' => 'MICE (Meeting, Incentive, Convention, Exhibition)',
                'program_keahlian' => 'Perhotelan dan Jasa Pariwisata',
                'bidang_keahlian' => 'Pariwisata',
                'description' => 'Mempelajari penyelenggaraan event bisnis dan konvensi.',
                'career_prospects' => 'Event organizer, convention planner, exhibition coordinator',
                'skills_learned' => 'Event management, venue arrangement, hospitality',
                'riasec_profile' => ['E', 'S', 'C'],
            ],

            // Program Keahlian: Kuliner
            [
                'code' => 'TB',
                'name' => 'Tata Boga',
                'program_keahlian' => 'Kuliner',
                'bidang_keahlian' => 'Pariwisata',
                'description' => 'Mempelajari seni memasak dan manajemen dapur profesional.',
                'career_prospects' => 'Chef, pastry chef, cook, food stylist, wirausaha kuliner',
                'skills_learned' => 'Memasak, pastry & bakery, food presentation, kitchen management',
                'riasec_profile' => ['A', 'R', 'E'],
            ],

            // Program Keahlian: Kecantikan dan Spa
            [
                'code' => 'TKR',
                'name' => 'Tata Kecantikan Kulit dan Rambut',
                'program_keahlian' => 'Kecantikan dan Spa',
                'bidang_keahlian' => 'Pariwisata',
                'description' => 'Mempelajari perawatan kecantikan kulit dan rambut.',
                'career_prospects' => 'Beautician, makeup artist, hairstylist, nail artist',
                'skills_learned' => 'Makeup, facial, hair treatment, nail art, skin care',
                'riasec_profile' => ['A', 'S', 'E'],
            ],
            [
                'code' => 'SPA',
                'name' => 'Spa dan Beauty Therapy',
                'program_keahlian' => 'Kecantikan dan Spa',
                'bidang_keahlian' => 'Pariwisata',
                'description' => 'Mempelajari terapi spa dan perawatan tubuh.',
                'career_prospects' => 'Spa therapist, massage therapist, wellness consultant',
                'skills_learned' => 'Body massage, aromatherapy, body treatment, reflexology',
                'riasec_profile' => ['S', 'A', 'E'],
            ],

            // Program Keahlian: Desain Fesyen
            [
                'code' => 'TBs',
                'name' => 'Tata Busana',
                'program_keahlian' => 'Desain Fesyen',
                'bidang_keahlian' => 'Pariwisata',
                'description' => 'Mempelajari desain dan pembuatan busana.',
                'career_prospects' => 'Fashion designer, tailor, pattern maker, costume designer',
                'skills_learned' => 'Desain busana, menjahit, pattern drafting, fashion illustration',
                'riasec_profile' => ['A', 'R', 'E'],
            ],

            // ================================================================
            // BIDANG KEAHLIAN: SENI DAN EKONOMI KREATIF
            // ================================================================

            // Program Keahlian: Seni Rupa
            [
                'code' => 'SR',
                'name' => 'Seni Rupa',
                'program_keahlian' => 'Seni Rupa',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni lukis, patung, dan seni rupa murni.',
                'career_prospects' => 'Seniman, ilustrator, art teacher, kurator galeri',
                'skills_learned' => 'Menggambar, melukis, mematung, art history',
                'riasec_profile' => ['A', 'I', 'S'],
            ],

            // Program Keahlian: Desain Interior
            [
                'code' => 'DI',
                'name' => 'Desain Interior dan Teknik Furnitur',
                'program_keahlian' => 'Desain Interior',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari desain interior ruangan dan furniture.',
                'career_prospects' => 'Interior designer, drafter interior, furniture designer',
                'skills_learned' => 'Desain interior, AutoCAD, SketchUp, 3D rendering',
                'riasec_profile' => ['A', 'R', 'I'],
            ],

            // Program Keahlian: Seni Musik
            [
                'code' => 'SM',
                'name' => 'Seni Musik',
                'program_keahlian' => 'Seni Musik',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari teori musik, instrumen, dan performance.',
                'career_prospects' => 'Musisi, music teacher, music arranger, session player',
                'skills_learned' => 'Teori musik, instrumen, vokal, music production',
                'riasec_profile' => ['A', 'S', 'E'],
            ],

            // Program Keahlian: Seni Tari
            [
                'code' => 'ST',
                'name' => 'Seni Tari',
                'program_keahlian' => 'Seni Tari',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari tari tradisional dan kontemporer.',
                'career_prospects' => 'Penari profesional, koreografer, dance teacher',
                'skills_learned' => 'Tari tradisional, tari modern, koreografi',
                'riasec_profile' => ['A', 'S', 'E'],
            ],

            // Program Keahlian: Seni Karawitan
            [
                'code' => 'SK',
                'name' => 'Seni Karawitan',
                'program_keahlian' => 'Seni Karawitan',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni musik tradisional gamelan.',
                'career_prospects' => 'Niyaga, pengrawit, dalang, sanggar seni',
                'skills_learned' => 'Gamelan, tembang, gending, seni pertunjukan tradisional',
                'riasec_profile' => ['A', 'S', 'I'],
            ],

            // Program Keahlian: Seni Pedalangan
            [
                'code' => 'SP',
                'name' => 'Seni Pedalangan',
                'program_keahlian' => 'Seni Pedalangan',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni pewayangan dan dalang.',
                'career_prospects' => 'Dalang, seniman wayang, pengajar seni tradisional',
                'skills_learned' => 'Pedalangan, suluk, antawecana, karawitan',
                'riasec_profile' => ['A', 'S', 'E'],
            ],

            // Program Keahlian: Seni Teater
            [
                'code' => 'STR',
                'name' => 'Seni Teater',
                'program_keahlian' => 'Seni Teater',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni akting dan produksi teater.',
                'career_prospects' => 'Aktor/aktris, sutradara, stage manager, scriptwriter',
                'skills_learned' => 'Akting, directing, stage management, drama',
                'riasec_profile' => ['A', 'E', 'S'],
            ],

            // Program Keahlian: Kriya Kreatif
            [
                'code' => 'KKB',
                'name' => 'Kriya Kreatif Batik dan Tekstil',
                'program_keahlian' => 'Kriya Kreatif',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni batik dan kriya tekstil.',
                'career_prospects' => 'Perajin batik, desainer tekstil, wirausaha batik',
                'skills_learned' => 'Membatik, desain motif, pewarnaan, kriya tekstil',
                'riasec_profile' => ['A', 'R', 'E'],
            ],
            [
                'code' => 'KKK',
                'name' => 'Kriya Kreatif Keramik',
                'program_keahlian' => 'Kriya Kreatif',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni keramik dan pottery.',
                'career_prospects' => 'Perajin keramik, ceramic artist, wirausaha kerajinan',
                'skills_learned' => 'Pembentukan keramik, glazing, firing, desain keramik',
                'riasec_profile' => ['A', 'R', 'I'],
            ],
            [
                'code' => 'KKL',
                'name' => 'Kriya Kreatif Logam dan Perhiasan',
                'program_keahlian' => 'Kriya Kreatif',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni kriya logam dan pembuatan perhiasan.',
                'career_prospects' => 'Perajin perhiasan, goldsmith, silversmith',
                'skills_learned' => 'Kriya logam, pembuatan perhiasan, finishing, desain',
                'riasec_profile' => ['A', 'R', 'I'],
            ],
            [
                'code' => 'KKKu',
                'name' => 'Kriya Kreatif Kulit',
                'program_keahlian' => 'Kriya Kreatif',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni kriya dari bahan kulit.',
                'career_prospects' => 'Perajin kulit, leather craftsman, wirausaha tas/sepatu',
                'skills_learned' => 'Pengolahan kulit, pola tas/sepatu, jahit kulit, finishing',
                'riasec_profile' => ['A', 'R', 'E'],
            ],
            [
                'code' => 'KKKy',
                'name' => 'Kriya Kreatif Kayu dan Rotan',
                'program_keahlian' => 'Kriya Kreatif',
                'bidang_keahlian' => 'Seni dan Ekonomi Kreatif',
                'description' => 'Mempelajari seni kriya kayu dan anyaman rotan.',
                'career_prospects' => 'Perajin kayu, furniture maker, wirausaha kerajinan',
                'skills_learned' => 'Ukir kayu, anyaman rotan, finishing, desain produk',
                'riasec_profile' => ['A', 'R', 'E'],
            ],
        ];

        foreach ($majors as $major) {
            SmkMajor::create($major);
        }
    }
}
