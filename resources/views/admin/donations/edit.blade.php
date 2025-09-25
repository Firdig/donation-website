<x-layouts.admin>
    <x-slot name="header">Edit Donasi</x-slot>
    
    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.donations.update', $donation) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Donasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $donation->title) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   placeholder="Masukkan judul donasi yang menarik"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="8"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                      placeholder="Jelaskan detail donasi, tujuan, dan manfaatnya untuk menarik donatur..."
                                      required>{{ old('description', $donation->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Target Dana (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="target_amount" 
                                   id="target_amount" 
                                   value="{{ old('target_amount', $donation->target_amount) }}"
                                   min="100000"
                                   step="10000"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   placeholder="1000000"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Minimal Rp 100.000</p>
                            @error('target_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Berakhir
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date" 
                                   value="{{ old('end_date', $donation->end_date ? $donation->end_date->format('Y-m-d') : '') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Opsional - Kosongkan jika tidak ada batas waktu</p>
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    required>
                                <option value="active" {{ old('status', $donation->status) === 'active' ? 'selected' : '' }}>
                                    Aktif - Dapat menerima donasi
                                </option>
                                <option value="inactive" {{ old('status', $donation->status) === 'inactive' ? 'selected' : '' }}>
                                    Tidak Aktif - Tidak dapat menerima donasi
                                </option>
                                <option value="completed" {{ old('status', $donation->status) === 'completed' ? 'selected' : '' }}>
                                    Selesai - Target tercapai/donasi selesai
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Donasi
                            </label>
                            @if($donation->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $donation->image) }}" 
                                         alt="Current image" 
                                         class="w-32 h-32 object-cover rounded-lg border">
                                    <p class="text-sm text-gray-500 mt-1">Gambar saat ini</p>
                                </div>
                            @endif
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   accept="image/*"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB. {{ $donation->image ? 'Upload gambar baru untuk mengganti yang lama.' : 'Gambar membantu menarik perhatian donatur.' }}</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Donation Progress Info -->
                        <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Donasi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        Rp {{ number_format($donation->current_amount, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500">Dana Terkumpul</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $donation->donors_count }}
                                    </div>
                                    <div class="text-sm text-gray-500">Total Donatur</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ number_format($donation->progress_percentage, 1) }}%
                                    </div>
                                    <div class="text-sm text-gray-500">Progress</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                                         style="width: {{ min(100, $donation->progress_percentage) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.donations.index') }}" 
                           class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Update Donasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Format Script -->
    <script>
        // Format target amount input
        document.getElementById('target_amount').addEventListener('input', function(e) {
            let value = e.target.value;
            // Remove non-digits
            value = value.replace(/\D/g, '');
            // Add thousand separators for display
            if (value) {
                e.target.setAttribute('data-raw', value);
            }
        });

        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if it doesn't exist
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'image-preview';
                        preview.className = 'w-32 h-32 object-cover rounded-lg border mt-2';
                        e.target.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.admin>