<x-layouts.admin>
    <x-slot name="header">Kelola Transaksi</x-slot>
    
    <div class="mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="donation_id" class="border-gray-300 rounded-md shadow-sm">
                <option value="">Semua Donasi</option>
                @foreach($donations as $donation)
                    <option value="{{ $donation->id }}" {{ request('donation_id') == $donation->id ? 'selected' : '' }}>
                        {{ Str::limit($donation->title, 30) }}
                    </option>
                @endforeach
            </select>
            
            <select name="status" class="border-gray-300 rounded-md shadow-sm">
                <option value="">Semua Status</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
            
            <input type="text" name="search" placeholder="Cari nama/email donatur..." 
                   value="{{ request('search') }}" 
                   class="border-gray-300 rounded-md shadow-sm">
                   
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Filter
            </button>
            
            @if(request()->hasAny(['donation_id', 'status', 'search']))
                <a href="{{ route('admin.transactions.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donatur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->donor_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->donor_email }}</div>
                                    @if($transaction->user)
                                        <div class="text-xs text-gray-400">ID User: {{ $transaction->user->id }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($transaction->donation->title, 40) }}</div>
                                @if($transaction->message)
                                    <div class="text-sm text-gray-500 italic">{{ Str::limit($transaction->message, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.transactions.updateStatus', $transaction) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="text-xs rounded-full border-0 py-1 px-3 font-medium
                                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Gagal</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $transaction->created_at->format('d M Y') }}</div>
                                <div class="text-xs">{{ $transaction->created_at->format('H:i') }}</div>
                                <div class="text-xs text-gray-400">{{ $transaction->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
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
                                <p class="text-lg">Belum ada transaksi.</p>
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
        function confirmDelete(id) {
            if (confirm('Yakin ingin menghapus transaksi ini? Dana akan dikurangi dari total donasi!')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-layouts.admin>