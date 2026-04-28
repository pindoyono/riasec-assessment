<div class="max-w-5xl mx-auto px-4">
    @if (session('error'))
        <div class="mb-3 p-2 bg-red-50 border border-red-200 rounded-lg">
            <span class="text-red-700 text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex gap-3">
        {{-- Left Sidebar - Navigation Grid --}}
        <div class="w-32 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-sm p-2 sticky top-4">
                <div class="grid grid-cols-4 gap-1">
                    @foreach ($questions as $index => $question)
                        <button wire:click="goToQuestion({{ $index }})"
                            class="w-6 h-6 rounded text-[10px] font-medium transition-all
                                {{ $currentIndex === $index
                                    ? 'bg-indigo-600 text-white'
                                    : (isset($answers[$question->id])
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200') }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
                <div class="mt-2 pt-2 border-t flex items-center justify-between text-[10px]">
                    <span class="text-gray-500">{{ count($answers) }}/{{ $questions->count() }}</span>
                    <span class="text-indigo-600 font-semibold">{{ $this->progressPercentage }}%</span>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            {{-- Header Info - Compact --}}
            <div class="bg-white rounded-lg shadow-sm px-3 py-2 mb-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-semibold text-gray-800">{{ $assessment->student->name }}</span>
                        <span class="text-xs text-gray-400 mx-2">•</span>
                        <span class="text-xs text-gray-500">{{ $assessment->student->school?->name ?? '-' }}</span>
                    </div>
                    <span class="text-xs text-gray-400 font-mono">{{ $assessment->assessment_code }}</span>
                </div>
            </div>

            @if ($this->currentQuestion)
                {{-- Question Card --}}
                <div id="question-card" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    {{-- Question Header --}}
                    <div
                        class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-between">
                        <span class="text-white text-sm font-medium">Soal {{ $currentIndex + 1 }}</span>
                        <span class="text-white/80 text-xs">dari {{ $questions->count() }}</span>
                    </div>

                    {{-- Question & Answers --}}
                    <div class="p-4">
                        <p class="text-base text-gray-800 leading-relaxed mb-4">
                            {{ $this->currentQuestion->question_text }}
                        </p>

                        {{-- Answer Options --}}
                        @php
                            $options = [
                                1 => 'Sangat Tidak Setuju',
                                2 => 'Tidak Setuju',
                                3 => 'Netral',
                                4 => 'Setuju',
                                5 => 'Sangat Setuju',
                            ];
                            $currentAnswer = $answers[$this->currentQuestion->id] ?? null;
                        @endphp

                        {{-- Mobile: compact radio list --}}
                        <div class="sm:hidden space-y-2">
                            <p class="text-[11px] text-gray-500 mb-1">
                                Pilih jawaban untuk langsung lanjut ke soal berikutnya.
                            </p>
                            @foreach ($options as $value => $label)
                                <label for="answer-mobile-{{ $this->currentQuestion->id }}-{{ $value }}"
                                    class="flex items-center gap-2 p-2 rounded-lg border transition-all duration-150 cursor-pointer
                                        {{ (int) $currentAnswer === $value
                                            ? 'border-indigo-500 bg-indigo-50'
                                            : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                                    <input id="answer-mobile-{{ $this->currentQuestion->id }}-{{ $value }}"
                                        type="radio" name="answer-mobile-{{ $this->currentQuestion->id }}"
                                        wire:click="answerQuestion({{ $this->currentQuestion->id }}, {{ $value }})"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                        @checked((int) $currentAnswer === $value)>
                                    <span
                                        class="w-5 h-5 rounded-full text-[11px] flex items-center justify-center font-medium
                                        {{ (int) $currentAnswer === $value ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $value }}
                                    </span>
                                    <span
                                        class="text-xs leading-tight {{ (int) $currentAnswer === $value ? 'text-indigo-700 font-medium' : 'text-gray-600' }}">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Tablet/Desktop: original option cards --}}
                        <div class="hidden sm:grid sm:grid-cols-5 gap-2">
                            @foreach ($options as $value => $label)
                                <button
                                    wire:click="answerQuestion({{ $this->currentQuestion->id }}, {{ $value }})"
                                    class="p-2 rounded-lg border-2 transition-all duration-150 text-center
                                        {{ (int) $currentAnswer === $value
                                            ? 'border-indigo-500 bg-indigo-50'
                                            : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                                    <div
                                        class="w-7 h-7 rounded-full flex items-center justify-center mx-auto mb-1
                                        {{ (int) $currentAnswer === $value ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $value }}
                                    </div>
                                    <span
                                        class="text-[10px] leading-tight block {{ (int) $currentAnswer === $value ? 'text-indigo-700 font-medium' : 'text-gray-500' }}">
                                        {{ $label }}
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="mt-4 flex items-center justify-between border-t pt-3">
                            <button wire:click="previousQuestion" @if ($currentIndex === 0) disabled @endif
                                class="px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Sebelumnya
                            </button>

                            @if ($currentIndex === $questions->count() - 1 && count($answers) >= $questions->count())
                                <button wire:click="completeAssessment"
                                    class="px-4 py-1.5 rounded-lg bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium hover:from-green-600 hover:to-emerald-600 flex items-center">
                                    Selesai
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            @else
                                <button wire:click="nextQuestion" @if ($currentIndex >= $questions->count() - 1) disabled @endif
                                    class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed flex items-center">
                                    Selanjutnya
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <h3 class="text-sm font-medium text-gray-800">Tidak ada pertanyaan</h3>
                    <p class="text-gray-500 text-xs mt-1">Hubungi admin.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('question-advanced', () => {
            if (window.innerWidth >= 640) {
                return;
            }

            const card = document.getElementById('question-card');
            if (!card) {
                return;
            }

            requestAnimationFrame(() => {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            });
        });
    });
</script>
