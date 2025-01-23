<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class approveController extends Controller
{
    public function approve(){
        $orders = Order::all();

        return view('Approver.approval', compact('orders'));
    }

    public function approveHistory(){
        $orders = Order::all();

        return view('Approver.aproved', compact('orders'));
    }

    public function show($id)
    {
        // Cari order berdasarkan ID
        $order = Order::with('items')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Format data untuk dikirim ke frontend
        $data = [
            'id' => $order->id,
            'noorder' => $order->noorder,
            'acara' => $order->acara,
            'tanggal_acara' => $order->tanggal_acara,
            'tanggal_yang_diharapkan' => $order->tanggal_yang_diharapkan,
            'items' => $order->items->map(function ($item) {
                return [
                    'name' => $item->name, // Kolom nama item
                    'quantity' => $item->quantity, // Kolom jumlah item
                ];
            }),
        ];

        // Kirim data sebagai JSON
        return response()->json($data);
    }
}
