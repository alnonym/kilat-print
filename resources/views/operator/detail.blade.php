@extends('layouts.app')
@section('title', 'Detail Produksi')

@section('content')
<a href="{{ route('operator.queue') }}" class="text-red-600 hover:underline mb-6 inline-block"><i class="fa-solid fa-arrow-left"></i> Kembali ke Antrian</a>

<div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold text-red-600 mb-2">{{ $order->order_number }}</h1>
    <p class="text-gray-600 mb-6">Pelanggan: {{ $order->user->name }}</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Item Details -->
        <div class="md:col-span-2 bg-white border border-gray-300 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Item</h2>

            @foreach($items as $item)
                <div class="border-b pb-4 mb-4">
                    <p class="font-semibold text-lg">{{ $item->product->name }}</p>

                    <div class="grid grid-cols-2 gap-4 mt-2 text-sm text-gray-600">
                        <div>
                            <p><strong>Bahan:</strong> {{ $item->material->name ?? 'N/A' }}</p>
                            <p><strong>Finishing:</strong> {{ $item->finishing->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p><strong>Qty:</strong> {{ $item->quantity }}</p>
                            <p><strong>Ukuran:</strong> {{ $item->custom_width ?? '-' }} x {{ $item->custom_height ?? '-' }} cm</p>
                        </div>
                    </div>

                    <!-- Files -->
                    <div class="mt-3 flex gap-4">
                        @if($item->raw_design_file)
                            <a href="{{ asset('storage/'.$item->raw_design_file) }}" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <i class="fa-solid fa-download"></i> File Desain Asli
                            </a>
                        @endif

                        @if($item->preview_mockup_file)
                            <button onclick="showMockup()" class="px-3 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700">
                                <i class="fa-solid fa-image"></i> Lihat Mockup
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Status Update Form -->
        <div class="bg-red-50 border border-red-300 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Status</h2>

            <form method="POST" action="{{ route('operator.update-status', $production->id) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Produksi</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        @foreach(['verifikasi' => 'Verifikasi', 'persetujuan_desain' => 'Persetujuan Desain', 'proses_produksi' => 'Proses Produksi', 'finishing' => 'Finishing', 'quality_check' => 'Quality Check', 'siap_dikirim' => 'Siap Dikirim'] as $key => $label)
                            <option value="{{ $key }}" {{ $production->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" class="w-full border border-gray-300 rounded px-3 py-2" rows="4">{{ $production->notes }}</textarea>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white font-semibold py-2 rounded hover:bg-red-700">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </form>

            <div class="mt-6 pt-6 border-t">
                <p class="text-sm text-gray-600"><strong>Status Sekarang:</strong></p>
                <p class="text-lg font-bold text-red-600">{{ str_replace('_', ' ', ucfirst($production->status)) }}</p>
            </div>
        </div>
    </div>

    <!-- Mockup Preview Modal (hidden) -->
    <div id="mockupModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Preview Mockup</h3>
                <button onclick="hideMockup()" class="text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-times text-2xl"></i>
                </button>
            </div>
            <img id="mockupImage" src="" alt="Mockup" class="w-full rounded">
        </div>
    </div>
</div>

<script>
function showMockup() {
    const dataUrl = @json($items->first()->preview_mockup_file ?? null);
    if (dataUrl) {
        document.getElementById('mockupImage').src = dataUrl;
        document.getElementById('mockupModal').classList.remove('hidden');
    }
}

function hideMockup() {
    document.getElementById('mockupModal').classList.add('hidden');
}
</script>
@endsection
