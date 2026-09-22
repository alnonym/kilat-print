@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<h1 class="text-3xl font-bold text-red-600 mb-8"><i class="fa-solid fa-receipt"></i> Pesanan Saya</h1>

<div class="space-y-4">
    @forelse($orders as $order)
        <div class="bg-white border border-gray-300 rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                <div>
                    <p class="text-sm text-gray-600">No. Pesanan</p>
                    <p class="font-semibold text-lg">{{ $order->order_number }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Total Harga</p>
                    <p class="font-semibold text-red-600 text-lg">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Status Pembayaran</p>
                    <span class="px-3 py-1 rounded text-sm font-semibold
                        @if($order->payment_status === 'verified')
                            bg-green-100 text-green-800
                        @elseif($order->payment_status === 'rejected')
                            bg-red-100 text-red-800
                        @else
                            bg-yellow-100 text-yellow-800
                        @endif
                    ">
                        @if($order->payment_status === 'verified')
                            <i class="fa-solid fa-check"></i> Terverifikasi
                        @elseif($order->payment_status === 'rejected')
                            <i class="fa-solid fa-times"></i> Ditolak
                        @else
                            <i class="fa-solid fa-hourglass-half"></i> Menunggu
                        @endif
                    </span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2">
                <a href="{{ route('customer.tracking', $order->order_number) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fa-solid fa-tracking"></i> Tracking
                </a>

                @if($order->payment_status === 'pending' && !$order->payment_proof)
                    <form method="POST" action="{{ route('customer.upload-payment', $order->id) }}" enctype="multipart/form-data" class="inline">
                        @csrf
                        <label class="inline-block">
                            <input type="file" name="payment_proof" accept="image/*,application/pdf" class="hidden" onchange="this.form.submit()" required>
                            <span class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 cursor-pointer inline-block">
                                <i class="fa-solid fa-upload"></i> Upload Bukti
                            </span>
                        </label>
                    </form>
                @endif

                <span class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <i class="fa-solid fa-inbox text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-600">Belum ada pesanan</p>
            <a href="/" class="mt-4 inline-block px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Mulai Pesan Sekarang
            </a>
        </div>
    @endforelse
</div>

{{ $orders->links() }}
@endsection
