@extends('layouts.app')
@section('title', 'Tracking - '.$order->order_number)

@section('content')
<a href="{{ route('customer.orders') }}" class="text-red-600 hover:underline mb-6 inline-block"><i class="fa-solid fa-arrow-left"></i> Kembali</a>

<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-red-600 mb-2">{{ $order->order_number }}</h1>
    <p class="text-gray-600 mb-8">Dibuat {{ $order->created_at->format('d M Y H:i') }}</p>

    <!-- Order Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-50 border border-blue-300 rounded-lg p-4">
            <p class="text-sm text-gray-600">Nilai Pesanan</p>
            <p class="text-2xl font-bold text-blue-600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4">
            <p class="text-sm text-gray-600">Status Pembayaran</p>
            <span class="text-sm font-semibold
                @if($order->payment_status === 'verified')
                    text-green-600
                @elseif($order->payment_status === 'rejected')
                    text-red-600
                @else
                    text-yellow-600
                @endif
            ">
                @if($order->payment_status === 'verified')
                    ✓ Terverifikasi
                @elseif($order->payment_status === 'rejected')
                    ✗ Ditolak
                @else
                    ⏳ Menunggu Verifikasi
                @endif
            </span>
        </div>

        <div class="bg-purple-50 border border-purple-300 rounded-lg p-4">
            <p class="text-sm text-gray-600">Metode Pengiriman</p>
            <p class="text-sm font-semibold text-purple-600">
                @if($order->shipping_method === 'delivery')
                    <i class="fa-solid fa-truck"></i> Pengiriman
                @else
                    <i class="fa-solid fa-store"></i> Ambil di Toko
                @endif
            </p>
        </div>
    </div>

    <!-- Items -->
    <div class="bg-white border border-gray-300 rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Pesanan</h2>
        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex justify-between items-start border-b pb-4">
                    <div>
                        <p class="font-semibold">{{ $item->product->name }}</p>
                        @if($item->material)
                            <p class="text-sm text-gray-600">Bahan: {{ $item->material->name }}</p>
                        @endif
                        @if($item->finishing)
                            <p class="text-sm text-gray-600">Finishing: {{ $item->finishing->name }}</p>
                        @endif
                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        @if($item->raw_design_file)
                            <a href="{{ asset('storage/'.$item->raw_design_file) }}" class="text-blue-600 text-sm hover:underline">
                                <i class="fa-solid fa-download"></i> Download File Asli
                            </a>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Production Timeline -->
    @if($production)
        <div class="bg-white border border-gray-300 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Timeline Produksi</h2>

            <div class="space-y-6">
                @foreach($status_timeline as $status_key => $status_label)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-white
                                @if($production->status === $status_key || in_array($status_key, array_keys(array_slice($status_timeline, 0, array_search($production->status, array_keys($status_timeline)) + 1))))
                                    bg-green-600
                                @else
                                    bg-gray-400
                                @endif
                            ">
                                @if($production->status === $status_key || in_array($status_key, array_keys(array_slice($status_timeline, 0, array_search($production->status, array_keys($status_timeline)) + 1))))
                                    <i class="fa-solid fa-check"></i>
                                @else
                                    <i class="fa-solid fa-circle"></i>
                                @endif
                            </div>
                            @if(!$loop->last)
                                <div class="w-1 h-16 bg-gray-300 mt-2"></div>
                            @endif
                        </div>

                        <div class="py-2">
                            <p class="font-semibold text-gray-800">{{ $status_label }}</p>
                            @if($production->status === $status_key)
                                <p class="text-sm text-red-600"><strong>Status Sekarang</strong></p>
                                @if($production->operator)
                                    <p class="text-sm text-gray-600">Operator: {{ $production->operator->name }}</p>
                                @endif
                            @elseif(in_array($status_key, array_keys(array_slice($status_timeline, 0, array_search($production->status, array_keys($status_timeline))))))
                                <p class="text-sm text-green-600">✓ Selesai</p>
                            @else
                                <p class="text-sm text-gray-500">Belum dimulai</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($production->notes)
                <div class="mt-6 p-4 bg-blue-50 border border-blue-300 rounded">
                    <p class="text-sm text-gray-600">Catatan Operator:</p>
                    <p class="text-gray-800">{{ $production->notes }}</p>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-8 bg-yellow-50 border border-yellow-300 rounded">
            <p class="text-yellow-800">Pesanan belum diverifikasi oleh admin</p>
        </div>
    @endif
</div>
@endsection
