<div class="max-w-md mx-auto px-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-white text-center">
            <h1 class="text-2xl font-bold">Selamat Datang</h1>
            <p class="mt-2 text-indigo-100">Sistem Asesmen Minat Bakat RIASEC</p>
        </div>

        <div class="p-6">
            @if (!$tokenValidated)
                {{-- Step 1: Token Validation --}}
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Masukkan Token Lokasi</h2>
                    <p class="text-sm text-gray-500 mt-1">Token diberikan oleh panitia di lokasi test</p>
                </div>

                <form wire:submit="validateToken">
                    @if ($error)
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-red-700 text-sm">{{ $error }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label for="token" class="block text-sm font-medium text-gray-700 mb-2">Token Lokasi
                            Test</label>
                        <input type="text" id="token" wire:model="token"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center text-xl font-mono uppercase tracking-wider"
                            placeholder="XXXXXXXX" maxlength="10" autocomplete="off">
                        @error('token')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                        wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait">
                        <span wire:loading.remove>Validasi Token</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">Belum terdaftar?
                        <a href="{{ route('assessment.register') }}"
                            class="text-indigo-600 hover:text-indigo-700 font-semibold">Daftar di sini</a>
                    </p>
                </div>
            @else
                {{-- Step 2: NISN Input --}}
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Token Valid!</h2>
                    <p class="text-sm text-gray-500 mt-1">Lokasi: <span
                            class="font-semibold text-indigo-600">{{ $school->name }}</span></p>
                </div>

                <form wire:submit="login">
                    @if ($error)
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-red-700 text-sm">{{ $error }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN (10
                            digit)</label>
                        <input type="text" id="nisn" wire:model="nisn"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center text-xl font-mono tracking-wider"
                            placeholder="1234567890" maxlength="10" autocomplete="off" inputmode="numeric">
                        @error('nisn')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                        wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait">
                        <span wire:loading.remove>Masuk</span>
                        <span wire:loading>Memproses...</span>
                    </button>

                    <button type="button" wire:click="backToToken"
                        class="w-full mt-3 bg-gray-100 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        ← Kembali ke Token
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">Belum terdaftar?
                        <a href="{{ route('assessment.register') }}"
                            class="text-indigo-600 hover:text-indigo-700 font-semibold">Daftar di sini</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tentang Tes RIASEC</h3>
        <p class="text-gray-600 text-sm mb-4">
            Tes RIASEC adalah asesmen minat karir berdasarkan teori John Holland yang mengklasifikasikan kepribadian ke
            dalam 6 tipe:
        </p>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="flex items-center">
                <span
                    class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold mr-2">R</span>
                <span class="text-gray-700">Realistic</span>
            </div>
            <div class="flex items-center">
                <span
                    class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-2">I</span>
                <span class="text-gray-700">Investigative</span>
            </div>
            <div class="flex items-center">
                <span
                    class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold mr-2">A</span>
                <span class="text-gray-700">Artistic</span>
            </div>
            <div class="flex items-center">
                <span
                    class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold mr-2">S</span>
                <span class="text-gray-700">Social</span>
            </div>
            <div class="flex items-center">
                <span
                    class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold mr-2">E</span>
                <span class="text-gray-700">Enterprising</span>
            </div>
            <div class="flex items-center">
                <span
                    class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold mr-2">C</span>
                <span class="text-gray-700">Conventional</span>
            </div>
        </div>
    </div>
</div>
