<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-8 text-center">
                    <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Platform Donasi</h1>
                    <p class="text-xl text-gray-600 mb-8">Mari bersama-sama membantu sesama melalui donasi yang bermakna</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('donations.index') }}" 
                           class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-200 inline-block">
                            Lihat Semua Donasi
                        </a>
                        @guest
                        <a href="{{ route('register') }}" 
                           class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition duration-200 inline-block">
                            Daftar Sekarang
                        </a>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- Featured Donations -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Donasi Terbaru</h2>
                    <!-- This will be loaded from donations.index -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>