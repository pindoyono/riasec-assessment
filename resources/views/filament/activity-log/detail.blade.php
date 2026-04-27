<div class="space-y-4 text-sm">
    @php
        $changes = $activity->attribute_changes ?? ($activity->properties ?? collect());
        if ($changes instanceof \Illuminate\Support\Collection) {
            $changes = $changes->toArray();
        }
        $old = $changes['old'] ?? [];
        $new = $changes['attributes'] ?? [];
    @endphp

    <div class="grid grid-cols-2 gap-3">
        <div>
            <p class="font-semibold text-gray-500 dark:text-gray-400">Log Name</p>
            <p class="mt-1">{{ $activity->log_name ?? '-' }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-500 dark:text-gray-400">Event</p>
            <p class="mt-1">{{ $activity->event ?? '-' }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-500 dark:text-gray-400">Model</p>
            <p class="mt-1">{{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }}
                #{{ $activity->subject_id ?? '-' }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-500 dark:text-gray-400">Dilakukan Oleh</p>
            <p class="mt-1">{{ optional($activity->causer)->name ?? '-' }}</p>
        </div>
        <div class="col-span-2">
            <p class="font-semibold text-gray-500 dark:text-gray-400">Waktu</p>
            <p class="mt-1">{{ $activity->created_at?->format('d M Y H:i:s') }}</p>
        </div>
    </div>

    @if (!empty($old) || !empty($new))
        <div class="mt-4 overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-300">Field</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-300">Nilai Lama</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-300">Nilai Baru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach (array_keys($new + $old) as $field)
                        <tr>
                            <td class="px-4 py-2 font-medium">{{ $field }}</td>
                            <td class="px-4 py-2 text-red-500">{{ $old[$field] ?? '-' }}</td>
                            <td class="px-4 py-2 text-green-600">{{ $new[$field] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-2 text-gray-400">Tidak ada data perubahan yang dicatat.</p>
    @endif
</div>
