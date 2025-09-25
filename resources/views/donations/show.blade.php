<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $donation->title }}
            </h2>
            <a href="{{ route('donations.index') }}" 
               class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Donasi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mobile Back Button -->
            <div class="mb-6 sm:hidden">
                <a href="{{ route('donations.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Donasi
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @if($donation->image)
                                <img src="{{ asset('storage/' . $donation->image) }}" alt="{{ $donation->title }}" class="w-full h-64 object-cover rounded-lg mb-6">
                            @endif

                            <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ $donation->title }}</h1>
                            
                            <!-- Description -->
                            <div class="prose max-w-none mb-8">
                                {!! nl2br(e($donation->description)) !!}
                            </div>

                            <!-- Progress Section -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                                <h3 class="text-lg font-semibold mb-4">Progress Donasi</h3>
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Dana Terkumpul</span>
                                        <span class="font-semibold">{{ number_format($donation->progress_percentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-green-400 to-blue-500 h-4 rounded-full transition-all duration-500" style="width: {{ $donation->progress_percentage }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-3">
                                        <span class="text-xl font-bold text-green-600">Rp {{ number_format($donation->current_amount, 0, ',', '.') }}</span>
                                        <span class="text-lg text-gray-600">dari Rp {{ number_format($donation->target_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <div class="text-2xl font-bold text-blue-600">{{ $donation->transactions()->where('status', 'completed')->count() }}</div>
                                        <div class="text-sm text-gray-600">Donatur</div>
                                    </div>
                                    @if($donation->end_date)
                                        <div>
                                            @if($donation->end_date->isPast())
                                                @php
                                                    $daysPassed = $donation->end_date->startOfDay()->diffInDays(now()->startOfDay());
                                                @endphp
                                                <div class="text-2xl font-bold text-red-600">
                                                    {{ $daysPassed ?: 'Hari ini' }} 
                                                    @if($daysPassed > 0) hari @endif
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $daysPassed > 0 ? 'yang lalu berakhir' : 'berakhir' }}
                                                </div>
                                            @else
                                                @php
                                                    $remainingDays = now()->startOfDay()->diffInDays($donation->end_date->startOfDay());
                                                    $remainingDays = $remainingDays ?: 1; // Minimal 1 hari jika hari yang sama
                                                @endphp
                                                <div class="text-2xl font-bold text-green-600">
                                                    {{ $remainingDays }} hari
                                                </div>
                                                <div class="text-sm text-gray-600">tersisa</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- End Date Warning -->
                            @if($donation->end_date)
                                <div class="mb-6 p-4 {{ $donation->end_date->isPast() ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }} border rounded-lg">
                                    <p class="{{ $donation->end_date->isPast() ? 'text-red-800' : 'text-yellow-800' }}">
                                        <strong>Batas Waktu:</strong> {{ $donation->end_date->format('d F Y') }}
                                        @if($donation->end_date->isPast())
                                            <span class="block text-sm mt-1">Donasi ini sudah berakhir</span>
                                        @else
                                            <span class="block text-sm mt-1">{{ $donation->end_date->diffForHumans() }}</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Donations -->
                    @if($recentTransactions->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-6">Donasi Terbaru</h3>
                                <div class="space-y-4">
                                    @foreach($recentTransactions as $transaction)
                                        <div class="flex justify-between items-start p-4 bg-gray-50 rounded-lg">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                                        {{ substr($transaction->donor_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $transaction->donor_name }}</p>
                                                        <p class="text-sm text-gray-600">{{ $transaction->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                                @if($transaction->message)
                                                    <p class="text-sm text-gray-700 italic">{{ $transaction->message }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-green-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-8">
                        <div class="p-6 text-gray-900">
                            @auth
                                @if($donation->status === 'active' && (!$donation->end_date || !$donation->end_date->isPast()))
                                    <h3 class="text-lg font-semibold mb-6">Berdonasi Sekarang</h3>
                                    
                                    <form action="{{ route('donations.donate', $donation) }}" method="POST" id="donationForm">
                                        @csrf
                                        
                                        <!-- Amount Selection -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Nominal</label>
                                            <div class="grid grid-cols-2 gap-2 mb-4">
                                                <button type="button" class="amount-btn bg-white border-2 border-gray-300 text-gray-700 py-3 px-3 rounded-lg text-sm hover:border-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-200 font-medium shadow-sm hover:shadow-md active-amount" onclick="setAmount(10000)">
                                                    Rp 10.000
                                                </button>
                                                <button type="button" class="amount-btn bg-white border-2 border-gray-300 text-gray-700 py-3 px-3 rounded-lg text-sm hover:border-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-200 font-medium shadow-sm hover:shadow-md active-amount" onclick="setAmount(25000)">
                                                    Rp 25.000
                                                </button>
                                                <button type="button" class="amount-btn bg-white border-2 border-gray-300 text-gray-700 py-3 px-3 rounded-lg text-sm hover:border-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-200 font-medium shadow-sm hover:shadow-md active-amount" onclick="setAmount(50000)">
                                                    Rp 50.000
                                                </button>
                                                <button type="button" class="amount-btn bg-white border-2 border-gray-300 text-gray-700 py-3 px-3 rounded-lg text-sm hover:border-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-200 font-medium shadow-sm hover:shadow-md active-amount" onclick="setAmount(100000)">
                                                    Rp 100.000
                                                </button>
                                                <button type="button" class="amount-btn bg-white border-2 border-gray-300 text-gray-700 py-3 px-3 rounded-lg text-sm hover:border-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-200 font-medium shadow-sm hover:shadow-md active-amount" onclick="setAmount(250000)">
                                                    Rp 250.000
                                                </button>
                                                <button type="button" class="amount-btn bg-white border-2 border-gray-300 text-gray-700 py-3 px-3 rounded-lg text-sm hover:border-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-200 font-medium shadow-sm hover:shadow-md active-amount" onclick="setAmount(500000)">
                                                    Rp 500.000
                                                </button>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Atau masukkan nominal lain</label>
                                                <input type="number" 
                                                       id="amount" 
                                                       name="amount" 
                                                       min="1000" 
                                                       max="100000000"
                                                       value="{{ old('amount') }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror" 
                                                       placeholder="Minimal Rp 1.000"
                                                       required>
                                                @error('amount')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Message -->
                                        <div class="mb-6">
                                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan (Opsional)</label>
                                            <textarea id="message" 
                                                      name="message" 
                                                      rows="3" 
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('message') border-red-500 @enderror" 
                                                      placeholder="Tulis pesan dukungan Anda...">{{ old('message') }}</textarea>
                                            @error('message')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Anonymous Option -->
                                        <div class="mb-6">
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       name="is_anonymous" 
                                                       value="1" 
                                                       {{ old('is_anonymous') ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Donasi sebagai Anonim</span>
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" 
                                                id="submitBtn"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-lg focus:ring-4 focus:ring-green-200 transition-all duration-200 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-lg">
                                            <span id="btnText" class="flex items-center justify-center text-white">
                                                <svg class="w-6 h-6 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                                <span class="text-gray-700 font-bold">Donasi Sekarang</span>
                                            </span>
                                            <span id="btnLoading" class="hidden flex items-center justify-center text-white">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-gray-700 font-bold">Memproses Donasi...</span>
                                            </span>
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">Donasi Tidak Tersedia</h4>
                                        <p class="text-gray-600">
                                            @if($donation->status !== 'active')
                                                Donasi ini sudah tidak aktif.
                                            @elseif($donation->end_date && $donation->end_date->isPast())
                                                Donasi ini sudah berakhir.
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Login untuk Berdonasi</h4>
                                    <p class="text-gray-600 mb-6">Silakan login atau daftar terlebih dahulu untuk dapat berdonasi</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('login') }}" 
                                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-all duration-200 block text-center font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                            <span class="flex items-center justify-center text-white">
                                                <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                                </svg>
                                                <span class="text-white font-semibold">Login</span>
                                            </span>
                                        </a>
                                        <a href="{{ route('register') }}" 
                                           class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-all duration-200 block text-center font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                            <span class="flex items-center justify-center text-white">
                                                <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                                </svg>
                                                <span class="text-white font-semibold">Daftar</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for amount buttons and form handling -->
    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
            
            // Reset all buttons to default state
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'border-blue-500', 'text-white');
                btn.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
            });
            
            // Set clicked button to active state
            event.target.classList.remove('bg-white', 'border-gray-300', 'text-gray-700');
            event.target.classList.add('bg-blue-500', 'border-blue-500', 'text-white');
        }

        // Reset all buttons when typing custom amount
        document.getElementById('amount').addEventListener('input', function() {
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'border-blue-500', 'text-white');
                btn.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
            });
        });

        // Form submission handling
        document.getElementById('donationForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            
            // Prevent double submission
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            
            // Re-enable after 5 seconds if something goes wrong
            setTimeout(() => {
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }, 5000);
        });
    </script>
</x-app-layout>