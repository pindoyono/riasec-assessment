@php
    $rows = $getState() ?? [];
@endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm border border-sky-200 rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-sky-100 text-sky-800">
                <th class="px-4 py-3 text-left font-semibold w-40">RIASEC</th>
                <th class="px-4 py-3 text-left font-semibold">Penjelasan</th>
                <th class="px-4 py-3 text-left font-semibold">Rekomendasi Jurusan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr class="border-t border-sky-100 align-top">
                    <td class="px-4 py-4 font-semibold text-gray-900 bg-white">
                        <div>{{ $row['name'] }}</div>
                        <div
                            class="mt-2 inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            {{ $row['code'] }} • {{ $row['score'] }}%
                        </div>
                    </td>
                    <td class="px-4 py-4 text-gray-700 leading-7 bg-white">
                        {{ $row['description'] }}
                    </td>
                    <td class="px-4 py-4 bg-amber-50 text-gray-800 leading-7">
                        @if (filled($row['recommendations']))
                            Berdasarkan Tes RIASEC Holland, kompetensi keahlian yang cocok antara lain:
                            {{ $row['recommendations'] }}.
                            <div class="mt-2 text-xs font-medium text-amber-700">
                                Total jurusan terkait: {{ $row['recommendation_count'] }}
                            </div>
                        @else
                            -
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
