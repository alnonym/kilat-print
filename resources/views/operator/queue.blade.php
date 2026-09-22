@extends('layouts.app')
@section('title', 'Antrian Produksi')

@section('content')
<h1 class="text-3xl font-bold text-red-600 mb-8"><i class="fa-solid fa-tasks"></i> Antrian Produksi</h1>

<div class="space-y-4">
    @forelse($productions as $production)
        <div class="bg-white border border-gray-300 rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase">No. Pesanan</p>
                    <p class="font-semibold">{{ $production->order->order_number }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase">Pelanggan</p>
                    <p class="text-sm">{{ $production->order->user->name }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase">Status</p>
                    <span class="px-3 py-1 rounded text-xs font-semibold
                        @if($production->status === 'siap_dikirim')
                            bg-green-100 text-green-800
                        @elseif($production->status === 'verifikasi')
                            bg-red-100 text-red-800
                        @else
                            bg-blue-100 text-blue-800
                        @endif
                    ">
                        {{ str_replace('_', ' ', ucfirst($production->status)) }}
                    </span>
                </div>

                <div class="text-right">
                    <a href="{{ route('operator.show', $production->id) }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-semibold">
                        <i class="fa-solid fa-arrow-right"></i> Buka
                    </a>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t text-sm text-gray-600">
                <strong>Items:</strong>
                @foreach($production->order->items as $item)
                    {{ $item->product->name }} (x{{ $item->quantity }})@if(!$loop->last), @endif
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <i class="fa-solid fa-inbox text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-600">Tidak ada pekerjaan yang ditugaskan</p>
        </div>
    @endforelse
</div>

{{ $productions->links() }}
@endsection
