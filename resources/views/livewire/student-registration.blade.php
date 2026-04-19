<div class="max-w-2xl mx-auto px-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-8 text-white text-center">
            <h1 class="text-2xl font-bold">Registrasi Peserta</h1>
            <p class="mt-2 text-emerald-100">Daftar untuk mengikuti Asesmen Minat Bakat RIASEC</p>
        </div>

        <div class="p-6">
            @if ($registered)
                {{-- Success State --}}
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Registrasi Berhasil!</h2>
                    <p class="text-gray-600 mb-6">Data Anda telah terdaftar. Silakan login untuk mengerjakan assessment.
                    </p>

                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <p class="text-sm text-gray-500 mb-2">NISN Anda</p>
                        <p class="text-2xl font-mono font-bold text-gray-800 tracking-wider">{{ $nisn }}</p>
                        <p class="text-xs text-gray-400 mt-2">Gunakan NISN ini untuk login dan mengerjakan assessment
                        </p>
                    </div>

                    <div class="space-y-3">
                        <button wire:click="goToLogin"
                            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200">
                            Login & Mulai Assessment
                        </button>
                    </div>
                </div>
            @else
                {{-- Registration Form --}}
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Isi Data Diri</h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data berikut untuk mendaftar sebagai peserta</p>
                </div>

                <form wire:submit="register">
                    @if ($error)
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-red-700 text-sm">{{ $error }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4">
                        {{-- Data Wajib --}}
                        <div class="border-b border-gray-200 pb-2 mb-2">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Data Wajib</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="nisn" wire:model="nisn"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-mono tracking-wider"
                                    placeholder="10 digit NISN" maxlength="10" inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('nisn')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="name" wire:model="name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Nama lengkap sesuai identitas">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="gender" value="L"
                                        class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-sm text-gray-700">Laki-laki</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="gender" value="P"
                                        class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-sm text-gray-700">Perempuan</span>
                                </label>
                            </div>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Test
                                <span class="text-red-500">*</span></label>
                            <select id="school_id" wire:model="school_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">-- Pilih Lokasi Test --</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}">
                                        {{ $school->name }}{{ $school->city ? ' - ' . $school->city : '' }}</option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Data Tambahan --}}
                        <div class="border-b border-gray-200 pb-2 mb-2 mt-6">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Data Tambahan</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                    Lahir</label>
                                <input type="text" id="birth_place" wire:model="birth_place"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Kota kelahiran">
                                @error('birth_place')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Lahir</label>
                                <input type="date" id="birth_date" wire:model="birth_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Asal
                                    Sekolah</label>
                                <input type="text" id="asal_sekolah" wire:model="asal_sekolah"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Nama sekolah asal">
                                @error('asal_sekolah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="class"
                                    class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                <input type="text" id="class" wire:model="class"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Contoh: 9A, 9B">
                                @error('class')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No.
                                    HP</label>
                                <input type="text" id="phone" wire:model="phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" wire:model="email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="email@contoh.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea id="address" wire:model="address" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Alamat lengkap"></textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Orang Tua/Wali</label>
                                <input type="text" id="parent_name" wire:model="parent_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Nama orang tua/wali">
                                @error('parent_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-1">No. HP
                                    Orang Tua/Wali</label>
                                <input type="text" id="parent_phone" wire:model="parent_phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="08xxxxxxxxxx">
                                @error('parent_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200"
                            wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait">
                            <span wire:loading.remove>Daftar Sekarang</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">Sudah terdaftar?
                        <a href="{{ route('assessment.login') }}"
                            class="text-emerald-600 hover:text-emerald-700 font-semibold">Login di sini</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
