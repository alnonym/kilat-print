<?php

namespace App\Http\Controllers;

use App\Models\{Order, Production, User};
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_payment' => Order::where('payment_status', 'pending')->count(),
            'verified_payment' => Order::where('payment_status', 'verified')->count(),
            'in_production' => Production::whereIn('status', ['proses_produksi', 'finishing'])->count(),
        ];

        $recent_orders = Order::with('user')->latest()->take(10)->get();
        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    public function orders()
    {
        $orders = Order::with('user', 'items')->latest()->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    public function verifyPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => 'verified']);

        Production::create([
            'order_id' => $order->id,
            'status' => 'verifikasi',
        ]);

        return back()->with('success', 'Pembayaran diverifikasi. Production task dibuat.');
    }

    public function assignOperator(Request $request, $production_id)
    {
        $request->validate(['operator_id' => 'required|exists:users,id']);
        $production = Production::findOrFail($production_id);
        $production->update(['operator_id' => $request->operator_id]);

        return back()->with('success', 'Operator berhasil ditugaskan.');
    }
}
