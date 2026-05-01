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
            {{-- Header Info --}}
            <div class="bg-white rounded-lg shadow-sm px-3 py-2 mb-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-semibold text-gray-800">{{ $assessment->student->name }}</span>
                        <span class="text-xs text-gray-400 mx-2">•</span>
                        <span class="text-xs text-gray-500">{{ $assessment->student->school?->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-medium">Forced
                            Choice</span>
                        <span class="text-xs text-gray-400 font-mono">{{ $assessment->assessment_code }}</span>
                    </div>
                </div>
            </div>

            @if ($this->currentQuestion)
                {{-- Question Card --}}
                <div id="question-card" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    {{-- Question Header --}}
                    <div
                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-between">
                        <span class="text-white text-sm font-medium">Soal {{ $currentIndex + 1 }}</span>
                        <span class="text-white/80 text-xs">dari {{ $questions->count() }}</span>
                    </div>

                    {{-- Question Body --}}
                    <div class="p-4">
                        <p class="text-sm text-gray-500 mb-3 italic">{{ $this->currentQuestion->prompt }}</p>

                        @php
                            $currentAnswer = $answers[$this->currentQuestion->id] ?? null;
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Option A --}}
                            <button wire:click="answerQuestion({{ $this->currentQuestion->id }}, 'A')"
                                class="group relative p-4 rounded-xl border-2 text-left transition-all duration-150
                                    {{ $currentAnswer === 'A'
                                        ? 'border-purple-500 bg-purple-50 shadow-md'
                                        : 'border-gray-200 hover:border-purple-300 hover:bg-purple-50/50' }}">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold
                                        {{ $currentAnswer === 'A' ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-purple-100 group-hover:text-purple-700' }}">
                                        A
                                    </span>
                                    <span
                                        class="text-sm leading-snug {{ $currentAnswer === 'A' ? 'text-purple-800 font-medium' : 'text-gray-700' }}">
                                        {{ $this->currentQuestion->option_a_text }}
                                    </span>
                                </div>
                                @if ($currentAnswer === 'A')
                                    <span class="absolute top-2 right-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </button>

                            {{-- Option B --}}
                            <button wire:click="answerQuestion({{ $this->currentQuestion->id }}, 'B')"
                                class="group relative p-4 rounded-xl border-2 text-left transition-all duration-150
                                    {{ $currentAnswer === 'B'
                                        ? 'border-indigo-500 bg-indigo-50 shadow-md'
                                        : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold
                                        {{ $currentAnswer === 'B' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-indigo-100 group-hover:text-indigo-700' }}">
                                        B
                                    </span>
                                    <span
                                        class="text-sm leading-snug {{ $currentAnswer === 'B' ? 'text-indigo-800 font-medium' : 'text-gray-700' }}">
                                        {{ $this->currentQuestion->option_b_text }}
                                    </span>
                                </div>
                                @if ($currentAnswer === 'B')
                                    <span class="absolute top-2 right-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </button>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="mt-4 flex items-center justify-between border-t pt-3">
                            <button wire:click="previousQuestion" @if ($currentIndex === 0) disabled @endif
                                class="px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 text-sm hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Sebelumnya
                            </button>

                            @if ($isCompleted)
                                <button wire:click="completeAssessment"
                                    class="px-4 py-1.5 rounded-lg bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium hover:from-green-600 hover:to-emerald-600 flex items-center">
                                    Lihat Hasil
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            @else
                                <button wire:click="nextQuestion" @if ($currentIndex >= $questions->count() - 1) disabled @endif
                                    class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed flex items-center">
                                    Selanjutnya
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <h3 class="text-sm font-medium text-gray-800">Tidak ada pertanyaan aktif</h3>
                    <p class="text-gray-500 text-xs mt-1">Hubungi admin.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('question-advanced', () => {
            if (window.innerWidth >= 640) return;
            const card = document.getElementById('question-card');
            if (!card) return;
            requestAnimationFrame(() => {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    });
</script>
