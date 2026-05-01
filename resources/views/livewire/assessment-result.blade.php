<div class="max-w-4xl mx-auto px-4">
    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-white text-center">
            <h1 class="text-2xl font-bold">Hasil Assessment RIASEC</h1>
            <p class="mt-2 text-indigo-100">Profil Minat Bakat Anda</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-center">
                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-semibold text-gray-800">{{ $assessment->student->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">NISN</p>
                    <p class="font-semibold text-gray-800">{{ $assessment->student->nisn ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Lokasi Tempat Test</p>
                    <p class="font-semibold text-gray-800">{{ $assessment->student->school?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Asal Sekolah</p>
                    <p class="font-semibold text-gray-800">{{ $assessment->student->asal_sekolah ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Tes</p>
                    <p class="font-semibold text-gray-800">{{ $assessment->completed_at?->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Kode RIASEC</p>
                    <p class="font-bold text-2xl text-indigo-600">{{ $assessment->riasec_code }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIASEC Scores --}}
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        {{-- Chart --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Grafik Skor RIASEC</h2>
            <div class="relative" style="height: 300px;">
                <canvas id="riasecChart"></canvas>
            </div>
        </div>

        {{-- Score List --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Skor</h2>
            <div class="space-y-4">
                @php
                    $scores = [
                        ['code' => 'R', 'name' => 'Realistic', 'score' => $assessment->score_r, 'color' => 'red'],
                        ['code' => 'I', 'name' => 'Investigative', 'score' => $assessment->score_i, 'color' => 'blue'],
                        ['code' => 'A', 'name' => 'Artistic', 'score' => $assessment->score_a, 'color' => 'yellow'],
                        ['code' => 'S', 'name' => 'Social', 'score' => $assessment->score_s, 'color' => 'green'],
                        ['code' => 'E', 'name' => 'Enterprising', 'score' => $assessment->score_e, 'color' => 'purple'],
                        ['code' => 'C', 'name' => 'Conventional', 'score' => $assessment->score_c, 'color' => 'gray'],
                    ];
                    $sortedScores = collect($scores)->sortByDesc('score');
                @endphp

                @foreach ($sortedScores as $index => $score)
                    <div class="flex items-center">
                        <span
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3 bg-indigo-100 text-indigo-600">
                            {{ $score['code'] }}
                        </span>
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $score['name'] }}</span>
                                <span class="text-sm font-bold text-indigo-600">
                                    {{ number_format($score['score'], 1) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 bg-indigo-500"
                                    style="width: {{ $score['score'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIASEC Detail Table --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <h2 class="text-lg font-semibold text-gray-800 px-6 pt-6 pb-3">Detail Profil RIASEC Anda</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-sky-100 text-sky-800">
                        <th class="px-4 py-3 text-left font-semibold w-28">RIASEC</th>
                        <th class="px-4 py-3 text-left font-semibold">Penjelasan</th>
                        <th class="px-4 py-3 text-left font-semibold">Rekomendasi Profesi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $riasecLetters = str_split($assessment->riasec_code ?? '');
                        $topScores = collect($scores)
                            ->filter(fn($s) => in_array($s['code'], $riasecLetters))
                            ->sortBy(fn($s) => array_search($s['code'], $riasecLetters));
                    @endphp
                    @foreach ($topScores as $s)
                        @php
                            $cat = $categories->firstWhere('code', $s['code']);
                            $majors = $allMajors->filter(fn($m) => in_array($s['code'], $m->riasec_profile ?? []));
                        @endphp
                        <tr class="border-b border-gray-100">
                            <td class="px-4 py-4 font-semibold text-gray-800 align-top">{{ $s['name'] }}</td>
                            <td class="px-4 py-4 text-gray-600 align-top leading-relaxed">
                                {{ $cat?->description ?? '-' }}</td>
                            <td class="px-4 py-4 align-top bg-yellow-50 leading-relaxed">
                                @if ($majors->count() > 0)
                                    <span class="text-gray-700">Berdasarkan Tes RIASEC Holland, kompetensi keahlian yang
                                        cocok antara lain:</span>
                                    @foreach ($majors as $m)
                                        <span class="text-gray-800">&bull; {{ $m->name }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Actions --}}
    <div class="bg-white rounded-xl shadow-lg p-6 text-center">
        <p class="text-gray-600 mb-4">Simpan hasil assessment Anda dengan mengunduh dalam format PDF.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('assessment.pdf', $assessment->assessment_code) }}" target="_blank"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Download PDF
            </a>
            <button
                onclick="var w=window.open('{{ url('assessment/pdf-preview/' . $assessment->assessment_code) }}','_blank');w.addEventListener('load',function(){w.print();})"
                class="hidden inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('riasecChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Skor RIASEC',
                    data: chartData.data,
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(99, 102, 241)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(99, 102, 241)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
