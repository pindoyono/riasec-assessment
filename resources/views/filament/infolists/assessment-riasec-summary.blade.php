@php
    $rows = $getState() ?? [];
@endphp

<div class="overflow-x-auto w-full">
    <table class="w-full text-sm border border-sky-200 rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-sky-100 text-sky-800">
                <th class="px-4 py-3 text-left font-semibold w-44">RIASEC</th>
                <th class="px-4 py-3 text-left font-semibold w-80">Penjelasan</th>
                <th class="px-4 py-3 text-left font-semibold">Rekomendasi Jurusan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                @php $isTop = $row['is_top'] ?? false; @endphp
                <tr class="border-t border-sky-100 align-top {{ $isTop ? 'bg-emerald-50' : '' }}">
                    <td class="px-4 py-4 font-semibold text-gray-900 {{ $isTop ? 'bg-emerald-50' : 'bg-white' }}">
                        <div class="flex items-center gap-2">
                            <span>{{ $row['name'] }}</span>
                            @if ($isTop)
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-300">
                                    Top
                                </span>
                            @endif
                        </div>
                        <div
                            class="mt-2 inline-flex items-center rounded-md {{ $isTop ? 'bg-emerald-100 text-emerald-800 ring-emerald-300' : 'bg-gray-100 text-gray-600 ring-gray-200' }} px-2 py-1 text-xs font-medium ring-1 ring-inset">
                            {{ $row['code'] }} • {{ $row['score'] }}%
                        </div>
                    </td>
                    <td class="px-4 py-4 text-gray-700 leading-6 {{ $isTop ? 'bg-emerald-50' : 'bg-white' }}">
                        {{ $row['description'] }}
                    </td>
                    <td class="px-4 py-4 text-gray-800 leading-6 {{ $isTop ? 'bg-amber-50' : 'bg-gray-50' }}">
                        @if (filled($row['recommendations']))
                            <p class="text-xs text-gray-500 mb-1">Berdasarkan Tes RIASEC Holland, kompetensi keahlian
                                yang cocok antara lain:</p>
                            <p>{{ $row['recommendations'] }}.</p>
                            <div class="mt-2 text-xs font-medium {{ $isTop ? 'text-amber-700' : 'text-gray-500' }}">
                                Total jurusan terkait: {{ $row['recommendation_count'] }}
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-4 text-center text-gray-500 bg-white">
                        Belum ada ringkasan rekomendasi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
