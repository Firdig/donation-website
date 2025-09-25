<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Donasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($donations as $donation)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden border hover:shadow-lg transition duration-300">
                                @if($donation->image)
                                    <img src="{{ asset('storage/' . $donation->image) }}" alt="{{ $donation->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold mb-3 text-gray-800">{{ $donation->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($donation->description, 120) }}</p>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mb-6">
                                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                                            <span>Terkumpul</span>
                                            <span class="font-semibold">{{ number_format($donation->progress_percentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="bg-gradient-to-r from-green-400 to-blue-500 h-3 rounded-full transition-all duration-500" style="width: {{ $donation->progress_percentage }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-700 mt-2">
                                            <span class="font-medium text-green-600">Rp {{ number_format($donation->current_amount, 0, ',', '.') }}</span>
                                            <span>Target: Rp {{ number_format($donation->target_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Meta Info -->
                                    <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                        <span>{{ $donation->transactions()->where('status', 'completed')->count() }} donatur</span>
                                        @if($donation->end_date)
                                            @if($donation->end_date->isPast())
                                                <span class="text-red-500">
                                                    {{ $donation->days_passed_since_end }} hari lalu berakhir
                                                </span>
                                            @else
                                                <span class="text-green-500">
                                                    {{ $donation->remaining_days }} hari tersisa
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <a href="{{ route('donations.show', $donation) }}" 
                                       class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 text-center block font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <span class="flex items-center justify-center text-gray-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Detail & Donasi
                                        </span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-16">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada donasi tersedia</h3>
                                <p class="text-gray-500">Silakan cek kembali nanti.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($donations->hasPages())
                        <div class="mt-8">
                            {{ $donations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>