<?php

namespace App\Http\Controllers;

use App\Models\{Production, Order};
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:operator']);
    }

    public function index()
    {
        $productions = Production::where('operator_id', auth()->id())
            ->with('order', 'order.items')
            ->latest()
            ->paginate(10);

        return view('operator.queue', compact('productions'));
    }

    public function show($id)
    {
        $production = Production::findOrFail($id);
        if ($production->operator_id !== auth()->id()) {
            abort(403);
        }

        $order = $production->order;
        $items = $order->items;

        return view('operator.detail', compact('production', 'order', 'items'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:verifikasi,persetujuan_desain,proses_produksi,finishing,quality_check,siap_dikirim']);

        $production = Production::findOrFail($id);
        if ($production->operator_id !== auth()->id()) {
            abort(403);
        }

        $production->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $production->notes,
        ]);

        return back()->with('success', 'Status diperbarui.');
    }
}
