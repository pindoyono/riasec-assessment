<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Hasil RIASEC – Forced Choice</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $assessment->student->name }} &bull;
                    {{ $assessment->student->school?->name ?? '-' }}</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black tracking-widest text-indigo-600">{{ $riasecCode }}</span>
                <p class="text-xs text-gray-400 mt-0.5">Kode RIASEC (Top 3)</p>
            </div>
        </div>
    </div>

    {{-- Score bars --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Distribusi Skor</h2>

        @php
            $maxScore = max(array_values($scores)) ?: 1;
            $typeColors = [
                'R' => ['bar' => 'bg-red-400', 'label' => 'Realistic', 'badge' => 'bg-red-100 text-red-700'],
                'I' => [
                    'bar' => 'bg-yellow-400',
                    'label' => 'Investigative',
                    'badge' => 'bg-yellow-100 text-yellow-700',
                ],
                'A' => ['bar' => 'bg-green-400', 'label' => 'Artistic', 'badge' => 'bg-green-100 text-green-700'],
                'S' => ['bar' => 'bg-blue-400', 'label' => 'Social', 'badge' => 'bg-blue-100 text-blue-700'],
                'E' => [
                    'bar' => 'bg-purple-400',
                    'label' => 'Enterprising',
                    'badge' => 'bg-purple-100 text-purple-700',
                ],
                'C' => ['bar' => 'bg-gray-400', 'label' => 'Conventional', 'badge' => 'bg-gray-100 text-gray-700'],
            ];
            $sortedScores = $scores;
            arsort($sortedScores);
        @endphp

        <div class="space-y-3">
            @foreach ($sortedScores as $type => $score)
                @php
                    $pct = round(($score / $maxScore) * 100);
                    $color = $typeColors[$type] ?? [
                        'bar' => 'bg-gray-400',
                        'label' => $type,
                        'badge' => 'bg-gray-100 text-gray-700',
                    ];
                @endphp
                <div class="flex items-center gap-3">
                    <span
                        class="w-7 h-7 rounded-full {{ $color['badge'] }} flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $type }}</span>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-0.5">
                            <span class="text-xs text-gray-600">{{ $color['label'] }}</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $score }} poin</span>
                        </div>
                        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $color['bar'] }} rounded-full transition-all duration-500"
                                style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Top 3 explanation --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Tipe Dominan Kamu</h2>
        <div class="grid grid-cols-3 gap-3">
            @foreach (str_split($riasecCode) as $rank => $code)
                @php
                    $color = $typeColors[$code] ?? ['badge' => 'bg-gray-100 text-gray-700', 'label' => $code];
                @endphp
                <div class="text-center p-3 rounded-lg border border-gray-100">
                    <div
                        class="text-2xl font-black {{ $color['badge'] === 'bg-gray-100 text-gray-700' ? 'text-gray-600' : '' }} inline-block w-10 h-10 rounded-full {{ $color['badge'] }} flex items-center justify-center mx-auto mb-1">
                        {{ $code }}</div>
                    <p class="text-xs text-gray-500">{{ ['1st', '2nd', '3rd'][$rank] ?? '' }}</p>
                    <p class="text-xs font-medium text-gray-700">{{ $typeColors[$code]['label'] ?? $code }}</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $scores[$code] ?? 0 }} poin</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Note about both systems --}}
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-sm text-indigo-700">
        <p class="font-medium mb-1">ℹ️ Tentang Hasil Ini</p>
        <p>Hasil ini dihitung dari metode <strong>Forced Choice</strong> – kamu dipaksa memilih salah satu aktivitas
            dari dua pilihan, sehingga hasilnya lebih mencerminkan preferensi asli dan menghindari skor yang terlalu
            merata.</p>
    </div>

</div>
