@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<h1 class="text-3xl font-bold text-red-600 mb-8"><i class="fa-solid fa-chart-line"></i> Dashboard Admin</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow p-6">
        <p class="text-sm text-gray-600">Total Pesanan</p>
        <p class="text-4xl font-bold text-blue-600">{{ $stats['total_orders'] }}</p>
    </div>

    <div class="bg-yellow-50 border-l-4 border-yellow-600 rounded-lg shadow p-6">
        <p class="text-sm text-gray-600">Menunggu Verifikasi</p>
        <p class="text-4xl font-bold text-yellow-600">{{ $stats['pending_payment'] }}</p>
    </div>

    <div class="bg-green-50 border-l-4 border-green-600 rounded-lg shadow p-6">
        <p class="text-sm text-gray-600">Terverifikasi</p>
        <p class="text-4xl font-bold text-green-600">{{ $stats['verified_payment'] }}</p>
    </div>

    <div class="bg-purple-50 border-l-4 border-purple-600 rounded-lg shadow p-6">
        <p class="text-sm text-gray-600">Sedang Produksi</p>
        <p class="text-4xl font-bold text-purple-600">{{ $stats['in_production'] }}</p>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white border border-gray-300 rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan Terbaru</h2>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">No. Pesanan</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Pelanggan</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Total</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Status Bayar</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-mono">{{ $order->order_number }}</td>
                        <td class="px-4 py-2 text-sm">{{ $order->user->name }}</td>
                        <td class="px-4 py-2 text-sm font-semibold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($order->payment_status === 'verified')
                                    bg-green-100 text-green-800
                                @elseif($order->payment_status === 'rejected')
                                    bg-red-100 text-red-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif
                            ">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm">
                            <a href="{{ route('admin.orders') }}" class="text-blue-600 hover:underline text-xs">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
