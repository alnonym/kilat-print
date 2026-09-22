@extends('layouts.app')
@section('title', 'Katalog Produk')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-red-600 mb-4">Katalog Produk Cetak</h1>
    <p class="text-gray-600">Pilih produk dan desain pesanan Anda</p>
</div>

<!-- Filter Kategori -->
<div class="mb-8 flex gap-4 flex-wrap">
    <a href="/" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition">
        <i class="fa-solid fa-th"></i> Semua
    </a>
    @if(isset($categories) && count($categories) > 0)
        @foreach($categories as $cat)
            <a href="?category={{ $cat->id }}" class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-red-600 hover:text-white transition">
                {{ $cat->name }}
            </a>
        @endforeach
    @endif
</div>

<!-- Product Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
    @if(isset($products) && count($products) > 0)
        @foreach($products as $product)
            <div class="bg-white border border-gray-300 rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fa-solid fa-image text-gray-400 text-4xl"></i>
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-red-600 font-bold text-lg">Rp{{ number_format($product->base_price, 0, ',', '.') }}</span>
                    </div>

                    <a href="/product/{{ $product->slug }}" class="w-full block text-center bg-red-600 text-white font-semibold py-2 rounded hover:bg-red-700 transition">
                        <i class="fa-solid fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-span-full text-center py-12">
            <i class="fa-solid fa-inbox text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-600">Tidak ada produk tersedia</p>
        </div>
    @endif
</div>

@endsection
