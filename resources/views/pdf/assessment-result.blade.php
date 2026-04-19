<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Assessment RIASEC - {{ $student->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #333;
        }

        .page {
            padding: 15px 20px;
        }

        /* ======= HEADER ======= */
        .header-box {
            display: table;
            width: 100%;
            background: #cce8f6;
            border: 1px solid #90c8e8;
            margin-bottom: 6px;
        }

        .header-logo-cell {
            display: table-cell;
            width: 130px;
            background: #f5c842;
            vertical-align: middle;
            text-align: center;
            padding: 5px;
        }

        .header-info-cell {
            display: table-cell;
            vertical-align: middle;
            padding: 10px 15px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .header-student-table {
            border-collapse: collapse;
            font-size: 10px;
        }

        .header-student-table td {
            padding: 1px 0;
            font-weight: bold;
        }

        .header-student-table td:first-child {
            width: 110px;
            font-weight: normal;
        }

        /* ======= SDS DESCRIPTION ======= */
        .sds-box {
            margin-bottom: 6px;
        }

        .sds-box h3 {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .sds-box p {
            font-size: 8px;
            color: #444;
            line-height: 1.5;
        }

        /* ======= PROFILE HEADING ======= */
        .profile-heading {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        /* ======= SCORE + CHART TABLE ======= */
        .summary-outer {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
            margin-bottom: 6px;
        }

        .summary-outer td {
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .score-inner {
            width: 100%;
            border-collapse: collapse;
        }

        .score-inner th {
            background: #e0f2fe;
            color: #0369a1;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #bae6fd;
            font-weight: bold;
            font-size: 10px;
        }

        .score-inner td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            font-size: 10px;
        }

        .bar-wrap {
            height: 20px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            display: flex;
            align-items: center;
            padding-left: 6px;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
        }

        .chart-header {
            background: #e0f2fe;
            color: #0369a1;
            padding: 5px 7px;
            font-weight: bold;
            font-size: 9px;
            border-bottom: 1px solid #bae6fd;
        }

        .chart-body {
            text-align: center;
            padding: 5px;
        }

        /* ======= RIASEC DETAIL TABLE ======= */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .detail-table th {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #bae6fd;
            font-weight: bold;
        }

        .detail-table td {
            padding: 7px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            line-height: 1.5;
        }

        .detail-table td:first-child {
            width: 70px;
            font-weight: bold;
            white-space: nowrap;
        }

        .detail-table td:nth-child(2) {
            width: 42%;
        }

        .detail-table td:nth-child(3) {
            background: #fef9c3;
        }

        /* ======= FOOTER ======= */
        .footer {
            margin-top: 6px;
            padding-top: 6px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 7px;
            color: #94a3b8;
        }
    </style>
</head>

<body>
    @php
        $scoreData = [
            ['code' => 'R', 'name' => 'Realistic', 'score' => $assessment->score_r, 'color' => '#e05252'],
            ['code' => 'I', 'name' => 'Investigative', 'score' => $assessment->score_i, 'color' => '#3b82f6'],
            ['code' => 'A', 'name' => 'Artistic', 'score' => $assessment->score_a, 'color' => '#eab308'],
            ['code' => 'S', 'name' => 'Social', 'score' => $assessment->score_s, 'color' => '#22c55e'],
            ['code' => 'E', 'name' => 'Enterprising', 'score' => $assessment->score_e, 'color' => '#8b5cf6'],
            ['code' => 'C', 'name' => 'Conventional', 'score' => $assessment->score_c, 'color' => '#6b7280'],
        ];

        $riasecLetters = str_split($assessment->riasec_code ?? '');
        $sortedScores = collect($scoreData)
            ->filter(fn($s) => in_array($s['code'], $riasecLetters))
            ->sortBy(fn($s) => array_search($s['code'], $riasecLetters));

        // Radar chart
        $cx = 90;
        $cy = 85;
        $maxR = 65;
        $angles = [-90, -30, 30, 90, 150, 210];
        $axisLabels = ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'];
        $vals = [
            $assessment->score_r,
            $assessment->score_i,
            $assessment->score_a,
            $assessment->score_s,
            $assessment->score_e,
            $assessment->score_c,
        ];
        $poly = '';
        foreach ($angles as $i => $ang) {
            $r = ($vals[$i] / 100) * $maxR;
            $poly .= round($cx + $r * cos(deg2rad($ang)), 2) . ',' . round($cy + $r * sin(deg2rad($ang)), 2) . ' ';
        }
    @endphp

    <div class="page">

        {{-- ===== HEADER ===== --}}
        <table class="header-box">
            <tr>
                <td class="header-logo-cell">
                    {{-- Hexagon RIASEC Logo --}}
                    <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/riasec-logo.svg'))) }}"
                        style="width: 100%; height: auto; display: block;" />
                </td>
                <td class="header-info-cell">
                    <div class="header-title">Hasil Assesmen Bakat Minat SDS</div>
                    <table class="header-student-table">
                        <tr>
                            <td>Nama</td>
                            <td>: {{ $student->name }}</td>
                        </tr>
                        <tr>
                            <td>NISN</td>
                            <td>: {{ $student->nisn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Sekolah</td>
                            <td>: {{ $student->school?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Tes</td>
                            <td>: {{ $assessment->completed_at?->format('d F Y') }}</td>
                        </tr>
                    </table>
                </td>
                <td
                    style="display: table-cell; width: 120px; vertical-align: middle; text-align: center; background: #4f46e5; padding: 10px 8px;">
                    <div style="font-size: 9px; font-weight: bold; color: #ffffff; margin-bottom: 4px;">Kode RIASEC Anda
                    </div>
                    <div style="font-size: 24px; font-weight: bold; letter-spacing: 4px; color: #ffffff;">
                        {{ $assessment->riasec_code }}</div>
                </td>
            </tr>
        </table>

        {{-- ===== SDS DESCRIPTION ===== --}}
        <div class="sds-box">
            <h3>SDS Assesment Test</h3>
            <p>SDS adalah singkatan dari Self Directed Search. Ini adalah instrumen terkini yang dikembangkan dari teori
                vokasional dan karir John Holland.
                SDS adalah instrumen penilaian karir dan eksplorasi minat yang akan memetakan aspirasi, aktifitas, dan
                bakat anda dengan beragam pilihan
                karir dan peluang pendidikan yang paling cocok untuk anda tekuni.</p>
        </div>

        {{-- ===== RINGKASAN ===== --}}
        <div class="profile-heading">Ringkasan Profil Bakat Minat Anda</div>

        {{-- Score Table + Radar Chart --}}
        <table class="summary-outer">
            <tr>
                {{-- Left: Score Table --}}
                <td style="width: 60%;">
                    <table class="score-inner">
                        <thead>
                            <tr>
                                <th style="width:28%">RIASEC</th>
                                <th style="width:10%">Skor</th>
                                <th>Presentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scoreData as $s)
                                @php $isTop3 = in_array($s['code'], $riasecLetters); @endphp
                                <tr style="{{ $isTop3 ? 'background:#dbeafe;font-weight:bold;' : '' }}">
                                    <td>{{ $s['name'] }}</td>
                                    <td style="text-align:center;">{{ round($s['score'] / 10) }}</td>
                                    <td>
                                        <div class="bar-wrap">
                                            <div class="bar-fill"
                                                style="width:{{ $s['score'] }}%;background:{{ $s['color'] }};">
                                                {{ $s['score'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                {{-- Right: Chart --}}
                <td style="width:40%;">
                    <div class="chart-header">Grafik</div>
                    <div class="chart-body">
                        @php
                            // Pre-compute all SVG data for DomPDF compatibility
                            $gridPolygons = [];
                            foreach ([0.2, 0.4, 0.6, 0.8, 1.0] as $lv) {
                                $pts = [];
                                foreach ($angles as $ang) {
                                    $pts[] =
                                        round($cx + $lv * $maxR * cos(deg2rad($ang)), 2) .
                                        ',' .
                                        round($cy + $lv * $maxR * sin(deg2rad($ang)), 2);
                                }
                                $gridPolygons[] = implode(' ', $pts);
                            }

                            $axisLines = [];
                            foreach ($angles as $ang) {
                                $axisLines[] = [
                                    'x2' => round($cx + $maxR * cos(deg2rad($ang)), 2),
                                    'y2' => round($cy + $maxR * sin(deg2rad($ang)), 2),
                                ];
                            }

                            $dots = [];
                            foreach ($angles as $i => $ang) {
                                $r = ($vals[$i] / 100) * $maxR;
                                $dots[] = [
                                    'cx' => round($cx + $r * cos(deg2rad($ang)), 2),
                                    'cy' => round($cy + $r * sin(deg2rad($ang)), 2),
                                ];
                            }

                            $labels = [];
                            $lr = $maxR + 13;
                            foreach ($angles as $i => $ang) {
                                $labels[] = [
                                    'x' => round($cx + $lr * cos(deg2rad($ang)), 2),
                                    'y' => round($cy + $lr * sin(deg2rad($ang)), 2) + 2,
                                    'text' => $axisLabels[$i],
                                ];
                            }
                        @endphp
                        <img src="data:image/svg+xml;base64,{{ base64_encode(
                            '
                                                                                                                                                                                                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="175" height="175" viewBox="0 0 180 175">
                                                                                                                                                                                                                                                                                ' .
                                implode(
                                    '',
                                    array_map(
                                        fn($p) => '<polygon points="' . $p . '" fill="none" stroke="#e2e8f0" stroke-width="0.5"/>',
                                        $gridPolygons,
                                    ),
                                ) .
                                '
                                                                                                                                                                                                                                                                                ' .
                                implode(
                                    '',
                                    array_map(
                                        fn($l) => '<line x1="' .
                                            $cx .
                                            '" y1="' .
                                            $cy .
                                            '" x2="' .
                                            $l['x2'] .
                                            '" y2="' .
                                            $l['y2'] .
                                            '" stroke="#cbd5e1" stroke-width="0.5"/>',
                                        $axisLines,
                                    ),
                                ) .
                                '
                                                                                                                                                                                                                                                                                <polygon points="' .
                                $poly .
                                '" fill="#b3e5fc" fill-opacity="0.4" stroke="#0ea5e9" stroke-width="1.5"/>
                                                                                                                                                                                                                                                                                ' .
                                implode(
                                    '',
                                    array_map(fn($d) => '<circle cx="' . $d['cx'] . '" cy="' . $d['cy'] . '" r="2.5" fill="#0ea5e9"/>', $dots),
                                ) .
                                '
                                                                                                                                                                                                                                                                                ' .
                                implode(
                                    '',
                                    array_map(
                                        fn($l) => '<text x="' .
                                            $l['x'] .
                                            '" y="' .
                                            $l['y'] .
                                            '" text-anchor="middle" font-size="6.5" fill="#555" font-family="DejaVu Sans, sans-serif">' .
                                            $l['text'] .
                                            '</text>',
                                        $labels,
                                    ),
                                ) .
                                '
                                                                                                                                                                                                                                                                                <rect x="' .
                                ($cx + 5) .
                                '" y="5" width="8" height="6" fill="#0ea5e9"/>
                                                                                                                                                                                                                                                                                <text x="' .
                                ($cx + 15) .
                                '" y="11" font-size="7" fill="#555" font-family="DejaVu Sans, sans-serif">SDS Holland</text>
                                                                                                                                                                                                                                                                            </svg>
                                                                                                                                                                                                                                                                        ',
                        ) }}"
                            width="175" height="175" />
                    </div>
                </td>
            </tr>
        </table>

        {{-- ===== RIASEC DETAIL TABLE ===== --}}
        <table class="detail-table">
            <thead>
                <tr>
                    <th>RIASEC</th>
                    <th>Penjelasan</th>
                    <th>Rekomendasi Profesi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sortedScores as $s)
                    @php
                        $cat = $categories->firstWhere('code', $s['code']);
                        $majors = $allMajors->filter(fn($m) => in_array($s['code'], $m->riasec_profile ?? []));
                    @endphp
                    <tr>
                        <td>{{ $s['name'] }}</td>
                        <td>{{ $cat?->description ?? '-' }}</td>
                        <td>
                            @if ($majors->count() > 0)
                                Berdasarkan Tes RIASEC Holland, kompetensi keahlian yang cocok antara lain:
                                @foreach ($majors as $m)
                                    &bull; {{ $m->name }}
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ===== FOOTER ===== --}}
        <div class="footer">
            <p>Kode Assessment: {{ $assessment->assessment_code }} | Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
            <p><em>Hasil assessment ini merupakan panduan awal. Konsultasikan dengan guru BK untuk mendapatkan arahan
                    lebih lanjut.</em></p>
        </div>
    </div>
</body>
