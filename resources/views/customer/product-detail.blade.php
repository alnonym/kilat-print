@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="mb-8">
    <a href="/" class="text-red-600 hover:underline"><i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Left: Editor & Mockup -->
    <div>
        <h2 class="text-2xl font-bold text-red-600 mb-4">
            <i class="fa-solid fa-palette"></i> Editor Desain
        </h2>

        <div class="bg-gray-100 rounded-lg p-4 mb-4">
            <canvas id="mockupCanvas" width="400" height="400" class="border-2 border-red-600 rounded"></canvas>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fa-solid fa-upload"></i> Unggah Desain Anda
            </label>
            <input type="file" id="designUploader" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2">
            <small class="text-gray-500">PNG, JPG, JPEG (max 5MB)</small>
        </div>

        <div class="bg-blue-50 border border-blue-300 rounded p-3 text-sm text-blue-800">
            <p><strong>Tip:</strong> Drag, zoom, dan rotate desain di canvas. Gunakan mouse wheel untuk zoom.</p>
        </div>
    </div>

    <!-- Right: Product Info & Order Form -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
        <p class="text-gray-600 mb-4">{{ $product->description }}</p>

        <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded">
            <p class="text-sm text-gray-600">Harga Dasar</p>
            <p class="text-3xl font-bold text-red-600">Rp{{ number_format($product->base_price, 0, ',', '.') }}</p>
        </div>

        <form id="orderForm" method="POST" action="{{ route('customer.process-order') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="preview_mockup_file" id="previewMockupInput">
            <input type="hidden" name="raw_design_file" id="rawDesignFileInput">

            <!-- Bahan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bahan</label>
                <select name="material_id" id="materialSelect" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($materials as $mat)
                        <option value="{{ $mat->id }}" data-price="{{ $mat->price_modifier }}">
                            {{ $mat->name }} (+Rp{{ number_format($mat->price_modifier, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Finishing -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Finishing</label>
                <select name="finishing_id" id="finishingSelect" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    <option value="">-- Pilih Finishing --</option>
                    @foreach($finishings as $fin)
                        <option value="{{ $fin->id }}" data-price="{{ $fin->price_modifier }}">
                            {{ $fin->name }} (+Rp{{ number_format($fin->price_modifier, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ukuran Custom -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lebar (cm)</label>
                    <input type="number" name="custom_width" step="0.1" class="w-full border border-gray-300 rounded px-4 py-2" placeholder="Optional">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tinggi (cm)</label>
                    <input type="number" name="custom_height" step="0.1" class="w-full border border-gray-300 rounded px-4 py-2" placeholder="Optional">
                </div>
            </div>

            <!-- Quantity -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                <input type="number" name="quantity" value="1" min="1" class="w-full border border-gray-300 rounded px-4 py-2" required id="quantityInput">
            </div>

            <!-- Shipping -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pengiriman</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="shipping_method" value="pickup" checked class="mr-2"> Ambil di toko
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="shipping_method" value="delivery" class="mr-2"> Pengiriman
                    </label>
                </div>
            </div>

            <div id="deliveryAddressDiv" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Pengiriman</label>
                <textarea name="delivery_address" class="w-full border border-gray-300 rounded px-4 py-2" rows="3"></textarea>
            </div>

            <!-- File Upload Hidden -->
            <input type="file" id="rawDesignInput" name="raw_design_file" accept="image/*" class="hidden" required>

            <!-- Total Price -->
            <div class="bg-yellow-50 border border-yellow-300 rounded p-4">
                <p class="text-sm text-gray-600">Total Estimasi</p>
                <p class="text-2xl font-bold text-yellow-700">Rp<span id="totalPrice">{{ number_format($product->base_price, 0, ',', '.') }}</span></p>
                <input type="hidden" name="total_price" id="totalPriceInput" value="{{ $product->base_price }}">
            </div>

            <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded hover:bg-red-700 transition">
                <i class="fa-solid fa-cart-plus"></i> Pesan Sekarang
            </button>
        </form>
    </div>
</div>

<script>
const canvas = new fabric.Canvas('mockupCanvas', {
    isDrawingMode: false,
    backgroundColor: '#f5f5f5'
});

const basePrice = {{ $product->base_price }};
let uploadedDesignFile = null;

// Load mockup template
@if($product->mockup_template_image)
const bgImg = new Image();
bgImg.src = '{{ asset('storage/'.$product->mockup_template_image) }}';
bgImg.onload = () => {
    canvas.setBackgroundImage(bgImg.src, canvas.renderAll, {
        scaleX: canvas.width / bgImg.width,
        scaleY: canvas.height / bgImg.height
    });
};
@endif

// Design upload
document.getElementById('designUploader').addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;
    uploadedDesignFile = file;
    const reader = new FileReader();
    reader.onload = event => {
        fabric.Image.fromURL(event.target.result, img => {
            canvas.getObjects().filter(o => o !== canvas.backgroundImage).forEach(o => canvas.remove(o));
            img.set({
                left: canvas.width / 2,
                top: canvas.height / 2,
                originX: 'center',
                originY: 'center',
                scaleX: 0.5,
                scaleY: 0.5
            });
            canvas.add(img);
            canvas.setActiveObject(img);
            canvas.renderAll();
        });
    };
    reader.readAsDataURL(file);
});

// Mouse wheel zoom
canvas.on('mouse:wheel', e => {
    const delta = e.e.deltaY;
    let zoom = canvas.getZoom();
    zoom *= Math.pow(0.999, delta);
    zoom = Math.max(0.5, Math.min(5, zoom));
    canvas.setZoom(zoom);
    e.e.preventDefault();
});

// Calculate total price
function updateTotalPrice() {
    let total = basePrice;
    const materialSelect = document.getElementById('materialSelect');
    const finishingSelect = document.getElementById('finishingSelect');
    const quantity = parseInt(document.getElementById('quantityInput').value) || 1;

    if (materialSelect.selectedIndex > 0) {
        const option = materialSelect.options[materialSelect.selectedIndex];
        total += parseFloat(option.dataset.price || 0);
    }
    if (finishingSelect.selectedIndex > 0) {
        const option = finishingSelect.options[finishingSelect.selectedIndex];
        total += parseFloat(option.dataset.price || 0);
    }

    total *= quantity;
    document.getElementById('totalPrice').textContent = new Intl.NumberFormat('id-ID').format(Math.round(total));
    document.getElementById('totalPriceInput').value = total;
}

document.getElementById('materialSelect').addEventListener('change', updateTotalPrice);
document.getElementById('finishingSelect').addEventListener('change', updateTotalPrice);
document.getElementById('quantityInput').addEventListener('change', updateTotalPrice);

// Shipping method toggle
document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', e => {
        document.getElementById('deliveryAddressDiv').classList.toggle('hidden', e.target.value === 'pickup');
    });
});

// Form submit
document.getElementById('orderForm').addEventListener('submit', e => {
    e.preventDefault();

    // Convert canvas to DataURL
    const previewDataUrl = canvas.toDataURL('image/png');
    document.getElementById('previewMockupInput').value = previewDataUrl;

    if (!uploadedDesignFile) {
        alert('Silakan unggah file desain');
        return;
    }

    // Create FormData
    const formData = new FormData(document.getElementById('orderForm'));
    formData.set('raw_design_file', uploadedDesignFile);

    fetch('{{ route('customer.process-order') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json().catch(() => r))
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            document.getElementById('orderForm').submit();
        }
    })
    .catch(err => console.error(err));
});
</script>
@endsection
