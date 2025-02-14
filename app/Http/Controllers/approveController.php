<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class approveController extends Controller
{
    public function approve(){
        $orders = Order::with(['user', 'items'])
            ->whereHas('user', function($query) {
                $query->where('satuan_kerja', Auth::user()->satuan_kerja);
            })
            ->where('status', '!=', 'setuju')
            ->get();

        return view('Approver.approval', compact('orders'));
    }

    public function approveHistory(){
        $orders = Order::with(['user', 'items'])
            ->whereHas('user', function($query) {
                $query->where('satuan_kerja', Auth::user()->satuan_kerja);
            })
            ->where('status', '=', 'setuju')
            ->get();

        return view('Approver.aproved', compact('orders'));
    }

    public function show($noorder)
    {
        // Cari order berdasarkan noorder
        $order = Order::with('items')->where('noorder', $noorder)->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Format data untuk dikirim ke frontend
        $data = [
            'noorder' => $order->noorder,
            'acara' => $order->acara,
            'tanggal_acara' => $order->tanggal_acara,
            'tanggal_yang_diharapkan' => $order->tanggal_yang_diharapkan,
            'penerima' => $order->user->name,
            'items' => $order->items->map(function ($item) {
                return [
                    'name' => $item->item, // Kolom nama item
                    'quantity' => $item->jumlah, // Kolom jumlah item
                ];
            }),
        ];

        // Kirim data sebagai JSON
        return response()->json($data);
    }

    public function updateStatus(Request $request, $noorder)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|string|in:Revisi,Edit,Tolak,Setuju',
            'note' => 'nullable|string|required_if:status,Revisi', // Note wajib diisi jika status Revisi
        ]);

        // Cari order berdasarkan nomor order
        $order = Order::where('noorder', $noorder)->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // Update status
        $order->status = $request->status;
        
        // Jika ada note dan status adalah Revisi, simpan note
        if ($request->status === 'Revisi' && $request->has('note')) {
            $order->revision_note = $request->note;
        }

        $order->save();

        return response()->json(['message' => 'Status berhasil diperbarui']);
    }

    public function edit($noorder)
    {
        $order = Order::where('noorder', $noorder)->first();
        $orderItems = OrderItem::where('noorder', $order->noorder)->get();
        $items = Barang::all(); // Get all available items
    
        return view('Approver.edit',compact('order','orderItems' ,'items'));
    }
    
    public function update(Request $request, $noorder)
    {
        $order = Order::where('noorder', $noorder)->first();
        
        $order->update([
            'acara' => $request->acara,
            'tanggal_acara' => $request->tanggal_acara,
            'tanggal_yang_diharapkan' => $request->tanggal_yang_diharapkan,
        ]);
    
        // Delete existing items
        OrderItem::where('noorder', $order->noorder)->delete();
    
        // Add new items
        foreach ($request->items as $item) {
            OrderItem::create([
                'noorder' => $order->noorder,
                'item' => $item['nama'],
                'jumlah' => $item['jumlah'],
            ]);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully'
        ]);
    }
}


   
