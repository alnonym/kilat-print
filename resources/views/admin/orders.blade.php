@extends('layouts.app')
@section('title', 'Verifikasi Pesanan - Admin')

@section('content')
<h1 class="text-3xl font-bold text-red-600 mb-8"><i class="fa-solid fa-list"></i> Daftar Pesanan</h1>

<div class="space-y-4">
    @forelse($orders as $order)
        <div class="bg-white border border-gray-300 rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                <div>
                    <p class="text-xs text-gray-500 uppercase">No. Pesanan</p>
                    <p class="font-semibold">{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->name }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase">Total</p>
                    <p class="text-lg font-bold text-red-600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase">Status Pembayaran</p>
                    <span class="inline-block px-3 py-1 rounded text-sm font-semibold
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
                </div>

                <div class="text-right">
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Items Preview -->
            <div class="mt-4 pt-4 border-t">
                <p class="text-sm font-semibold text-gray-700 mb-2">Item Pesanan:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    @foreach($order->items as $item)
                        <li>
                            • {{ $item->product->name }}
                            @if($item->material) ({{ $item->material->name }}) @endif
                            @if($item->finishing) - {{ $item->finishing->name }} @endif
                            x{{ $item->quantity }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Actions -->
            <div class="mt-4 pt-4 border-t flex gap-2">
                @if($order->payment_status === 'pending' && $order->payment_proof)
                    <form method="POST" action="{{ route('admin.verify-payment', $order->id) }}" class="inline">
                        @csrf
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-semibold">
                            <i class="fa-solid fa-check"></i> Verifikasi Pembayaran
                        </button>
                    </form>
                    <button onclick="alert('Lihat bukti di dashboard')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold">
                        <i class="fa-solid fa-eye"></i> Lihat Bukti
                    </button>
                @elseif($order->payment_status === 'verified')
                    @if($order->production)
                        <div class="text-sm">
                            <p class="text-green-600 font-semibold"><i class="fa-solid fa-check"></i> Sudah dalam produksi</p>
                            @if(!$order->production->operator_id)
                                <form method="POST" action="{{ route('admin.assign-operator', $order->production->id) }}" class="mt-2 inline">
                                    @csrf
                                    <select name="operator_id" class="px-3 py-1 border rounded text-sm" onchange="this.form.submit()">
                                        <option value="">-- Pilih Operator --</option>
                                        @foreach(\App\Models\User::where('role', 'operator')->get() as $op)
                                            <option value="{{ $op->id }}">{{ $op->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                <p class="text-sm text-gray-600">Operator: {{ $order->production->operator->name }}</p>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <p class="text-gray-600">Belum ada pesanan</p>
        </div>
    @endforelse
</div>

{{ $orders->links() }}
@endsection
