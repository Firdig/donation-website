<x-layouts.admin>
    <x-slot name="header">Tambah Donasi Baru</x-slot>
    
    <div class="max-w-4xl mx-auto">
        <!-- Alert untuk error umum -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Terdapat {{ $errors->count() }} kesalahan pada form
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.donations.store') }}" enctype="multipart/form-data" id="donationForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul Donasi -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Donasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror" 
                                   placeholder="Masukkan judul donasi yang menarik"
                                   required>
                            
                            <!-- Character counter -->
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">Minimal 10 karakter, rekomendasi maksimal 255 karakter</p>
                                <p class="text-xs" id="title-counter-text">
                                    <span id="title-count">0</span> karakter
                                </p>
                            </div>
                            
                            @error('title')
                                <div class="flex items-center mt-2 text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                            
                            <div class="text-xs mt-1 hidden" id="title-error"></div>
                            <div class="text-xs mt-1 hidden" id="title-warning"></div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="8"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror" 
                                      placeholder="Jelaskan detail donasi, tujuan, dan manfaatnya untuk menarik donatur..."
                                      required>{{ old('description') }}</textarea>
                            
                            <!-- Character counter -->
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">Minimal 50 karakter untuk deskripsi yang informatif, rekomendasi maksimal 5000 karakter</p>
                                <p class="text-xs" id="description-counter-text">
                                    <span id="description-count">0</span> karakter
                                </p>
                            </div>
                            
                            @error('description')
                                <div class="flex items-center mt-2 text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                            
                            <div class="text-xs mt-1 hidden" id="description-error"></div>
                            <div class="text-xs mt-1 hidden" id="description-warning"></div>
                        </div>

                        <!-- Target Dana -->
                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Target Dana (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="text" 
                                       name="target_amount_display" 
                                       id="target_amount_display" 
                                       class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('target_amount') border-red-500 @enderror" 
                                       placeholder="1.000.000"
                                       required>
                                <input type="hidden" name="target_amount" id="target_amount" value="{{ old('target_amount') }}">
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="font-medium">Batasan:</span> Minimal Rp 100.000 - Maksimal Rp 10.000.000.000
                            </p>
                            
                            @error('target_amount')
                                <div class="flex items-center mt-2 text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                            
                            <div class="text-xs text-red-600 mt-1 hidden" id="target_amount-error"></div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Berakhir
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date" 
                                   value="{{ old('end_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   max="{{ date('Y-m-d', strtotime('+1 year')) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('end_date') border-red-500 @enderror">
                            
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="font-medium">Opsional</span> - Kosongkan jika tidak ada batas waktu. Maksimal 1 tahun dari sekarang.
                            </p>
                            
                            @error('end_date')
                                <div class="flex items-center mt-2 text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                            
                            <div class="text-xs text-red-600 mt-1 hidden" id="end_date-error"></div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror" 
                                    required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                    ✓ Aktif - Dapat menerima donasi
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    ✕ Tidak Aktif - Tidak dapat menerima donasi
                                </option>
                            </select>
                            
                            <p class="text-xs text-gray-500 mt-1">
                                Pilih "Aktif" agar donasi dapat langsung menerima donatur
                            </p>
                            
                            @error('status')
                                <div class="flex items-center mt-2 text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <!-- Gambar -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Donasi
                            </label>
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('image') border-red-500 @enderror">
                            
                            <!-- Image preview -->
                            <div id="image-preview" class="mt-3 hidden">
                                <p class="text-xs text-gray-600 mb-2">Preview:</p>
                                <img id="preview-img" src="" alt="Preview" class="max-w-full h-40 object-cover rounded-md border border-gray-300">
                            </div>
                            
                            <div class="mt-2 space-y-1">
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">Format:</span> JPG, PNG, GIF
                                </p>
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">Ukuran:</span> Maksimal 2MB (2.048 KB)
                                </p>
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">Rekomendasi:</span> Resolusi minimal 800x600px untuk hasil terbaik
                                </p>
                            </div>
                            
                            @error('image')
                                <div class="flex items-center mt-2 text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                            
                            <div class="text-xs text-red-600 mt-1 hidden" id="image-error"></div>
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
                                id="submit-btn"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Simpan Donasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Konfirmasi Data</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modal-message"></p>
                </div>
                <div class="items-center px-4 py-3 space-x-3">
                    <button id="modal-confirm" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700">
                        Ya, Lanjutkan
                    </button>
                    <button id="modal-cancel" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                        Cek Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('donationForm');
            const submitBtn = document.getElementById('submit-btn');
            
            // Form elements
            const title = document.getElementById('title');
            const description = document.getElementById('description');
            const targetAmountDisplay = document.getElementById('target_amount_display');
            const targetAmount = document.getElementById('target_amount');
            const endDate = document.getElementById('end_date');
            const image = document.getElementById('image');
            const status = document.getElementById('status');

            // Character counters
            const titleCount = document.getElementById('title-count');
            const descriptionCount = document.getElementById('description-count');

            // Update character counters
            title.addEventListener('input', function() {
                const length = this.value.length;
                titleCount.textContent = length;
                
                const counterText = document.getElementById('title-counter-text');
                
                if (length > 255) {
                    counterText.classList.add('text-orange-600', 'font-semibold');
                    counterText.classList.remove('text-gray-500', 'text-green-600');
                    showFieldWarning('title', 'title-warning', `⚠️ Judul sudah ${length} karakter. Melebihi rekomendasi 255 karakter. Judul terlalu panjang mungkin kurang menarik.`);
                    
                    // Show popup notification every 100 characters
                    if (length % 100 === 0 && length > 255) {
                        showNotification('warning', 'Peringatan Panjang Judul', [
                            `Judul Anda sudah mencapai ${length} karakter`,
                            'Judul yang terlalu panjang dapat mengurangi daya tarik donasi',
                            'Pertimbangkan untuk mempersingkat judul'
                        ]);
                    }
                } else if (length >= 200) {
                    counterText.classList.add('text-orange-500');
                    counterText.classList.remove('text-gray-500', 'text-green-600');
                    showFieldWarning('title', 'title-warning', `ℹ️ Judul mendekati batas rekomendasi (${length}/255 karakter)`);
                } else if (length >= 10) {
                    counterText.classList.add('text-green-600');
                    counterText.classList.remove('text-gray-500', 'text-orange-600');
                    hideFieldWarning('title', 'title-warning');
                } else {
                    counterText.classList.add('text-gray-500');
                    counterText.classList.remove('text-green-600', 'text-orange-600');
                    hideFieldWarning('title', 'title-warning');
                }
                
                validateTitle();
            });

            description.addEventListener('input', function() {
                const length = this.value.length;
                descriptionCount.textContent = length;
                
                const counterText = document.getElementById('description-counter-text');
                
                if (length > 5000) {
                    counterText.classList.add('text-orange-600', 'font-semibold');
                    counterText.classList.remove('text-gray-500', 'text-green-600');
                    showFieldWarning('description', 'description-warning', `⚠️ Deskripsi sudah ${length} karakter. Melebihi rekomendasi 5000 karakter. Deskripsi yang terlalu panjang mungkin tidak dibaca sampai habis oleh donatur.`);
                    
                    // Show popup notification every 500 characters
                    if (length % 500 === 0 && length > 5000) {
                        showNotification('warning', 'Peringatan Panjang Deskripsi', [
                            `Deskripsi Anda sudah mencapai ${length.toLocaleString('id-ID')} karakter`,
                            'Deskripsi yang terlalu panjang dapat membuat donatur kehilangan minat',
                            'Pertimbangkan untuk mempersingkat atau membuat poin-poin penting'
                        ]);
                    }
                } else if (length >= 4000) {
                    counterText.classList.add('text-orange-500');
                    counterText.classList.remove('text-gray-500', 'text-green-600');
                    showFieldWarning('description', 'description-warning', `ℹ️ Deskripsi mendekati batas rekomendasi (${length}/5000 karakter)`);
                } else if (length >= 50) {
                    counterText.classList.add('text-green-600');
                    counterText.classList.remove('text-gray-500', 'text-orange-600');
                    hideFieldWarning('description', 'description-warning');
                } else {
                    counterText.classList.add('text-gray-500');
                    counterText.classList.remove('text-green-600', 'text-orange-600');
                    hideFieldWarning('description', 'description-warning');
                }
            });

            // Initialize counters
            if (title.value) titleCount.textContent = title.value.length;
            if (description.value) descriptionCount.textContent = description.value.length;

            // Format currency input
            targetAmountDisplay.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value) {
                    targetAmount.value = value;
                    e.target.value = formatRupiah(value);
                } else {
                    targetAmount.value = '';
                    e.target.value = '';
                }
            });

            // Initialize display if old value exists
            if (targetAmount.value) {
                targetAmountDisplay.value = formatRupiah(targetAmount.value);
            }

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Validate title
            function validateTitle() {
                const value = title.value.trim();
                const length = value.length;
                
                if (length === 0) {
                    showFieldError(title, 'title-error', '❌ Judul donasi wajib diisi');
                    return false;
                } else if (length < 10) {
                    showFieldError(title, 'title-error', `⚠️ Judul terlalu pendek (${length}/10 karakter minimum)`);
                    return false;
                } else {
                    hideFieldError(title, 'title-error');
                    return true;
                }
            }

            // Validate description
            function validateDescription() {
                const value = description.value.trim();
                const length = value.length;
                
                if (length === 0) {
                    showFieldError(description, 'description-error', '❌ Deskripsi donasi wajib diisi');
                    return false;
                } else if (length < 50) {
                    showFieldError(description, 'description-error', `⚠️ Deskripsi terlalu pendek (${length}/50 karakter minimum). Tambahkan detail untuk menarik donatur.`);
                    return false;
                } else {
                    hideFieldError(description, 'description-error');
                    return true;
                }
            }

            // Validate target amount
            function validateTargetAmount() {
                const value = parseInt(targetAmount.value); //#1
                const minAmount = 100000; //#2
                const maxAmount = 10000000000; //#3
                
                if (!value || value === 0) { //Decission Value Kosong atau tidak  #4, Decission 1 
                    showFieldError(targetAmountDisplay, 'target_amount-error', '❌ Target dana wajib diisi'); //#5, 
                    return false;//#6
                } else if (value < minAmount) { //Decission Value kurang dari minimal #7, Decission 2
                    showFieldError(targetAmountDisplay, 'target_amount-error', `⚠️ Target dana minimal Rp ${formatRupiah(minAmount.toString())} (Rp 100 ribu)`); //#8
                    return false; //#9
                } else if (value > maxAmount) { //Decission Value lebih dari maksimal #10, Decission 3
                    showFieldError(targetAmountDisplay, 'target_amount-error', `❌ Target dana maksimal Rp ${formatRupiah(maxAmount.toString())} (Rp 10 miliar)`); //#11
                    return false; //#12
                } else {
                    hideFieldError(targetAmountDisplay, 'target_amount-error'); //#13
                    return true; //#14
                }
            }

            // Validate end date
            function validateEndDate() {
                if (!endDate.value) {
                    hideFieldError(endDate, 'end_date-error');
                    return true;
                }
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const selected = new Date(endDate.value);
                const maxDate = new Date();
                maxDate.setFullYear(maxDate.getFullYear() + 1);
                
                if (selected <= today) {
                    showFieldError(endDate, 'end_date-error', '❌ Tanggal berakhir harus minimal besok');
                    return false;
                } else if (selected > maxDate) {
                    showFieldError(endDate, 'end_date-error', '⚠️ Tanggal berakhir maksimal 1 tahun dari sekarang');
                    return false;
                } else {
                    hideFieldError(endDate, 'end_date-error');
                    return true;
                }
            }

            // Validate image
            function validateImage() {
                if (image.files.length === 0) {
                    hideFieldError(image, 'image-error');
                    return true;
                }
                
                const file = image.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                
                if (!validTypes.includes(file.type)) {
                    showFieldError(image, 'image-error', '❌ Format file tidak valid. Gunakan JPG, PNG, atau GIF');
                    return false;
                } else if (file.size > maxSize) {
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    showFieldError(image, 'image-error', `❌ Ukuran file terlalu besar (${sizeMB}MB). Maksimal 2MB`);
                    return false;
                } else {
                    hideFieldError(image, 'image-error');
                    return true;
                }
            }

            // Event listeners for real-time validation
            title.addEventListener('blur', validateTitle);
            description.addEventListener('blur', validateDescription);
            targetAmountDisplay.addEventListener('blur', validateTargetAmount);
            endDate.addEventListener('blur', validateEndDate);
            
            // Image preview and validation
            image.addEventListener('change', function() {
                validateImage();
                
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-img').src = e.target.result;
                        document.getElementById('image-preview').classList.remove('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    document.getElementById('image-preview').classList.add('hidden');
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate all fields
                const isTitleValid = validateTitle();
                const isDescriptionValid = validateDescription();
                const isTargetValid = validateTargetAmount();
                const isDateValid = validateEndDate();
                const isImageValid = validateImage();
                
                const errors = [];
                
                if (!isTitleValid) errors.push('Judul donasi tidak valid');
                if (!isDescriptionValid) errors.push('Deskripsi donasi tidak valid');
                if (!isTargetValid) errors.push('Target dana tidak valid');
                if (!isDateValid) errors.push('Tanggal berakhir tidak valid');
                if (!isImageValid) errors.push('File gambar tidak valid');
                
                if (errors.length > 0) {
                    showNotification('error', 'Gagal Menyimpan', errors);
                    
                    // Scroll to first error
                    const firstError = form.querySelector('.border-red-500');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                } else {
                    // Show confirmation modal
                    showConfirmationModal();
                }
            });

            // Modal functions
            let formConfirmed = false;
            
            function showConfirmationModal() {
                const modal = document.getElementById('confirmModal');
                const message = `Apakah Anda yakin ingin menyimpan donasi "${title.value.substring(0, 50)}${title.value.length > 50 ? '...' : ''}" dengan target Rp ${formatRupiah(targetAmount.value)}?`;
                
                document.getElementById('modal-message').textContent = message;
                modal.classList.remove('hidden');
            }

            document.getElementById('modal-confirm').addEventListener('click', function() {
                formConfirmed = true;
                document.getElementById('confirmModal').classList.add('hidden');
                
                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                `;
                
                form.submit();
            });

            document.getElementById('modal-cancel').addEventListener('click', function() {
                document.getElementById('confirmModal').classList.add('hidden');
            });

            // Helper functions
            function showFieldError(field, errorId, message) {
                field.classList.add('border-red-500', 'bg-red-50');
                field.classList.remove('border-gray-300');
                const errorElement = document.getElementById(errorId);
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }

            function hideFieldError(field, errorId) {
                field.classList.remove('border-red-500', 'bg-red-50');
                field.classList.add('border-gray-300');
                const errorElement = document.getElementById(errorId);
                errorElement.classList.add('hidden');
            }

            function showFieldWarning(fieldName, warningId, message) {
                const warningElement = document.getElementById(warningId);
                warningElement.textContent = message;
                warningElement.classList.remove('hidden');
                warningElement.classList.add('text-orange-600', 'bg-orange-50', 'p-2', 'rounded', 'border', 'border-orange-200', 'flex', 'items-start');
                warningElement.innerHTML = `
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs">${message}</span>
                `;
            }

            function hideFieldWarning(fieldName, warningId) {
                const warningElement = document.getElementById(warningId);
                warningElement.classList.add('hidden');
            }

            function showNotification(type, title, messages) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 max-w-md bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden z-50 animate-slide-in';
                
                const bgColor = type === 'error' ? 'bg-red-50' : type === 'warning' ? 'bg-yellow-50' : 'bg-green-50';
                const borderColor = type === 'error' ? 'border-red-500' : type === 'warning' ? 'border-yellow-500' : 'border-green-500';
                const textColor = type === 'error' ? 'text-red-800' : type === 'warning' ? 'text-yellow-800' : 'text-green-800';
                const iconPath = type === 'error' 
                    ? 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z'
                    : type === 'warning'
                    ? 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z'
                    : 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z';
                
                notification.innerHTML = `
                    <div class="p-4 ${bgColor} border-l-4 ${borderColor}">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 ${textColor}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium ${textColor}">${title}</h3>
                                ${messages.length > 0 ? `
                                <div class="mt-2 text-sm ${textColor}">
                                    <ul class="list-disc list-inside space-y-1">
                                        ${messages.map(msg => `<li>${msg}</li>`).join('')}
                                    </ul>
                                </div>
                                ` : ''}
                            </div>
                            <div class="ml-auto pl-3">
                                <button onclick="this.closest('.fixed').remove()" class="inline-flex ${textColor} hover:opacity-75 focus:outline-none">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Auto remove after 6 seconds
                setTimeout(() => {
                    notification.style.animation = 'slide-out 0.3s ease-out';
                    setTimeout(() => notification.remove(), 300);
                }, 6000);
            }

            // Warning if leaving page with unsaved changes
            let formChanged = false;
            
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    formChanged = true;
                });
            });

            window.addEventListener('beforeunload', function(e) {
                if (formChanged && !formConfirmed) {
                    e.preventDefault();
                    e.returnValue = '';
                    return '';
                }
            });
        });
    </script>

    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slide-out {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }

        /* Custom scrollbar for textarea */
        #description::-webkit-scrollbar {
            width: 8px;
        }

        #description::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        #description::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        #description::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Disable spinner on number input */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Focus styles */
        input:focus, textarea:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        input.border-red-500:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
    </style>
</x-layouts.admin>