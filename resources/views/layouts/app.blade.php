<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Kilat Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
</head>
<body class="bg-white text-gray-800">
    <!-- Navbar -->
    <nav class="bg-red-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-print text-yellow-400 text-2xl"></i>
                <a href="/" class="text-2xl font-bold">Kilat Print</a>
            </div>

            <div class="flex items-center gap-6">
                @auth
                    <span class="text-sm">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                    
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-yellow-300"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                    @elseif(auth()->user()->role === 'operator')
                        <a href="{{ route('operator.queue') }}" class="hover:text-yellow-300"><i class="fa-solid fa-tasks"></i> Antrian</a>
                    @else
                        <a href="{{ route('customer.orders') }}" class="hover:text-yellow-300"><i class="fa-solid fa-receipt"></i> Pesanan</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="hover:text-yellow-300"><i class="fa-solid fa-sign-out-alt"></i> Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-yellow-300"><i class="fa-solid fa-sign-in-alt"></i> Login</a>
                    <a href="{{ route('register') }}" class="bg-yellow-500 text-red-600 px-4 py-2 rounded font-semibold hover:bg-yellow-400"><i class="fa-solid fa-user-plus"></i> Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 max-w-7xl mx-auto mt-4 rounded">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 max-w-7xl mx-auto mt-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t border-gray-300 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm text-gray-600">
            <p class="font-semibold text-gray-800">PT SOLUSI PRINT CEPAT</p>
            <p>Jl. H. Muchtar Raya, RT 10/RW 11, Petukangan Utara, Pesanggrahan, Jakarta Selatan</p>
            <p class="mt-2">Kilat Print &copy; 2026 - Sistem Informasi Pemesanan dan Manajemen Produksi</p>
        </div>
    </footer>
</body>
</html>
