<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category, Order, OrderItem, Material, Finishing, Production};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        // Hardcoded sample data untuk demo
        $categories = [
            (object)['id' => 1, 'name' => 'Mug Custom', 'slug' => 'mug-custom'],
            (object)['id' => 2, 'name' => 'Banner', 'slug' => 'banner'],
            (object)['id' => 3, 'name' => 'Kaos Sablon', 'slug' => 'kaos-sablon'],
        ];

        $category_id = request('category');
        
        $all_products = [
            (object)['id' => 1, 'category_id' => 1, 'name' => 'Mug Keramik Premium 400ml', 'slug' => 'mug-keramik-premium-400ml', 'description' => 'Mug berkualitas tinggi dari keramik pilihan', 'base_price' => 75000],
            (object)['id' => 2, 'category_id' => 2, 'name' => 'Banner Vinyl 3x1m', 'slug' => 'banner-vinyl-3x1m', 'description' => 'Banner vinyl tahan cuaca, cetak digital full color', 'base_price' => 450000],
            (object)['id' => 3, 'category_id' => 3, 'name' => 'Kaos Sablon DTG Premium', 'slug' => 'kaos-sablon-dtg-premium', 'description' => 'Kaos 100% cotton dengan sablon DTG berkualitas', 'base_price' => 85000],
        ];

        $products = $category_id 
            ? array_filter($all_products, fn($p) => $p->category_id == $category_id)
            : $all_products;

        return view('customer.index', compact('products', 'categories', 'category_id'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $materials = $product->materials;
        $finishings = $product->finishings;

        return view('customer.product-detail', compact('product', 'materials', 'finishings'));
    }

    public function processOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'material_id' => 'nullable|exists:materials,id',
            'finishing_id' => 'nullable|exists:finishings,id',
            'quantity' => 'required|integer|min:1',
            'custom_width' => 'nullable|numeric',
            'custom_height' => 'nullable|numeric',
            'raw_design_file' => 'required|file|mimes:png,jpg,jpeg,pdf|max:5120',
            'preview_mockup_file' => 'required|string',
            'total_price' => 'required|numeric',
            'shipping_method' => 'required|in:pickup,delivery',
            'delivery_address' => 'nullable|string',
        ]);

        $order_number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_number' => $order_number,
            'user_id' => auth()->id(),
            'total_price' => $validated['total_price'],
            'shipping_method' => $validated['shipping_method'],
            'delivery_address' => $validated['delivery_address'],
        ]);

        $design_path = $request->file('raw_design_file')->store('designs', 'public');

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $validated['product_id'],
            'material_id' => $validated['material_id'],
            'finishing_id' => $validated['finishing_id'],
            'quantity' => $validated['quantity'],
            'custom_width' => $validated['custom_width'],
            'custom_height' => $validated['custom_height'],
            'raw_design_file' => $design_path,
            'preview_mockup_file' => $validated['preview_mockup_file'],
            'subtotal' => $validated['total_price'],
        ]);

        return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dibuat. Silakan upload bukti pembayaran.');
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->with('items')->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $this->authorize('view', $order);

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $proof_path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $order->update([
            'payment_proof' => $proof_path,
            'payment_status' => 'pending',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    public function tracking($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $this->authorize('view', $order);

        $production = $order->production;
        $status_timeline = [
            'verifikasi' => 'Verifikasi',
            'persetujuan_desain' => 'Persetujuan Desain',
            'proses_produksi' => 'Proses Produksi',
            'finishing' => 'Finishing',
            'quality_check' => 'Quality Check',
            'siap_dikirim' => 'Siap Dikirim',
        ];

        return view('customer.tracking', compact('order', 'production', 'status_timeline'));
    }
}
