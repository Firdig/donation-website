<x-layouts.admin>
    <x-slot name="header">Detail Donasi: {{ $donation->title }}</x-slot>
    
    <div class="space-y-6">
        <!-- Donation Info Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Donasi</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.donations.edit', $donation) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                            Edit Donasi
                        </a>
                        <a href="{{ route('donations.show', $donation) }}" target="_blank"
                           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                            </svg>
                            Lihat di Website
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        @if($donation->image)
                            <img src="{{ asset('storage/' . $donation->image) }}" alt="{{ $donation->title }}" class="w-full h-64 object-cover rounded-lg mb-6 shadow-md">
                        @endif

                        <h1 class="text-2xl font-bold mb-6 text-gray-800">{{ $donation->title }}</h1>
                        
                        <div class="prose max-w-none mb-6 text-gray-700 leading-relaxed">
                            {!! nl2br(e($donation->description)) !!}
                        </div>
                    </div>

                    <div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-6 text-gray-900">Statistik Donasi</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <p class="text-sm text-gray-600">Target Dana</p>
                                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($donation->target_amount, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Dana Terkumpul</p>
                                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($donation->current_amount, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Progress</p>
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-green-400 to-blue-500 h-4 rounded-full transition-all duration-300" style="width: {{ min(100, $donation->progress_percentage) }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600 mt-2">
                                        <span class="font-semibold">{{ number_format($donation->progress_percentage, 1) }}%</span>
                                        <span>{{ $donation->transactions()->where('status', 'completed')->count() }} donatur</span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                        {{ $donation->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($donation->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </div>

                                @if($donation->end_date)
                                    <div>
                                        <p class="text-sm text-gray-600">Berakhir</p>
                                        <p class="text-lg font-semibold">{{ $donation->end_date->format('d F Y') }}</p>
                                        @if($donation->end_date->isPast())
                                            <span class="text-red-600 text-sm">(Sudah Berakhir)</span>
                                        @else
                                            <span class="text-green-600 text-sm">({{ $donation->end_date->diffForHumans() }})</span>
                                        @endif
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm text-gray-600">Dibuat</p>
                                    <p class="text-sm text-gray-900">{{ $donation->created_at->format('d F Y H:i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $donation->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">
                        Daftar Transaksi ({{ $transactions->total() }})
                    </h3>
                    <div class="text-sm text-gray-500">
                        Total Dana: <span class="font-semibold text-green-600">Rp {{ number_format($donation->transactions()->where('status', 'completed')->sum('amount'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donatur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                            {{ substr($transaction->donor_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->donor_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->donor_email }}</div>
                                            @if($transaction->user)
                                                <div class="text-xs text-gray-400">User ID: {{ $transaction->user->id }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs">
                                        @if($transaction->message)
                                            <p class="truncate" title="{{ $transaction->message }}">{{ $transaction->message }}</p>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $transaction->created_at->format('d M Y') }}</div>
                                    <div class="text-xs">{{ $transaction->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete({{ $transaction->id }})" 
                                            class="text-red-600 hover:text-red-900" title="Hapus">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $transaction->id }}" method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8z"/>
                                </svg>
                                <p class="text-lg font-medium">Belum ada transaksi</p>
                                <p class="text-sm text-gray-400 mt-1">yang sesuai dengan filter</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->withQueryString()->links() }}
            </div>
        @endif
    </div>
    
    <script>
        function updateStatus(selectElement, transactionId) {
            if (confirm('Update status transaksi ini?')) {
                selectElement.form.submit();
            } else {
                // Reset to original value
                selectElement.selectedIndex = selectElement.getAttribute('data-original-index') || 0;
            }
        }
        
        function confirmDelete(id) {
            if (confirm('Yakin ingin menghapus transaksi ini?\n\nDana akan dikurangi dari total donasi dan aksi ini tidak dapat dibatalkan!')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
        
        // Store original selected index for reset
        document.querySelectorAll('select[name="status"]').forEach(select => {
            select.setAttribute('data-original-index', select.selectedIndex);
        });
    </script>
</x-layouts.admin>