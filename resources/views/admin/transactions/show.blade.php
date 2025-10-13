<x-layouts.admin>
    <x-slot name="header">Detail Transaksi #{{ $transaction->id }}</x-slot>
    
    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                        </svg>
                        Informasi Transaksi
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                           ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
            
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Transaction Details -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Detail Transaksi</h4>
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">ID Transaksi</dt>
                                <dd class="text-sm text-gray-900 font-mono">#{{ $transaction->id }}</dd>
                            </div>
                            
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Jumlah Donasi</dt>
                                <dd class="text-lg font-bold text-green-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</dd>
                            </div>
                            
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                                <dd class="text-sm text-gray-900">{{ $transaction->created_at->format('d F Y H:i:s') }}</dd>
                            </div>
                            
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Terakhir Update</dt>
                                <dd class="text-sm text-gray-900">{{ $transaction->updated_at->format('d F Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Donor Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Donatur</h4>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                    {{ substr($transaction->donor_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">{{ $transaction->donor_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $transaction->donor_email }}</p>
                                </div>
                            </div>
                            
                            @if($transaction->user)
                                <div class="border-t border-gray-200 pt-4">
                                    <p class="text-sm text-gray-600">Akun User Terdaftar:</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $transaction->user->name }} (ID: {{ $transaction->user->id }})</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Donation Information -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Donasi Untuk</h4>
                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ $transaction->donation->title }}</h5>
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($transaction->donation->description, 200) }}</p>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Target:</span>
                                        <span class="font-semibold">Rp {{ number_format($transaction->donation->target_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Terkumpul:</span>
                                        <span class="font-semibold text-green-600">Rp {{ number_format($transaction->donation->current_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.donations.show', $transaction->donation) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 ml-4">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Message -->
                @if($transaction->message)
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Pesan Donatur</h4>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <p class="text-gray-700 italic">"{{ $transaction->message }}"</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('admin.transactions.updateStatus', $transaction) }}" class="flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <label class="text-sm font-medium text-gray-700">Update Status:</label>
                            <select name="status" class="border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm transition duration-200">
                                Update
                            </button>
                        </form>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.transactions.index') }}" 
                           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                            Kembali
                        </a>
                        
                        <button onclick="confirmDelete()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200">
                            Hapus Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <form id="delete-form" method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    
    <script>
        function confirmDelete() {
            if (confirm('Yakin ingin menghapus transaksi ini?\n\nDana akan dikurangi dari total donasi dan aksi ini tidak dapat dibatalkan!')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
</x-layouts.admin>