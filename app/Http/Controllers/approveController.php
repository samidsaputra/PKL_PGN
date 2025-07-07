<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class approveController extends Controller
{
    public function approve()
    {
        $orders = Order::with(['user', 'items'])
            ->whereHas('user', function ($query) {
                $query->where('satuan_kerja', Auth::user()->satuan_kerja);
            })
            ->whereNotIn('status', ['setuju', 'tolak']) // hanya status selain setuju & tolak
            ->orderBy('created_at', 'desc')
            ->get();

            return view('Approver.approval', compact('orders'));
        }
    

    public function approveHistory()
    {
        $orders = Order::with(['user', 'items'])
            ->whereHas('user', function ($query) {
                $query->where('satuan_kerja', Auth::user()->satuan_kerja);
            })
            ->whereIn('status', ['setuju', 'tolak']) // tampilkan order setuju & tolak
            ->orderBy('created_at', 'desc')
            ->get();

        $orders = Order::with(['user', 'items'])
    ->whereHas('user', function ($query) {
        $query->where('satuan_kerja', Auth::user()->satuan_kerja);
    })
    ->whereIn('status', ['setuju', 'tolak'])
    ->orderBy('created_at', 'desc') // urutkan dari yang terbaru
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

        // Jika status Tolak, kembalikan stok barang
        if ($request->status === 'Tolak') {
            $orderItems = OrderItem::where('noorder', $noorder)->get();
            foreach ($orderItems as $item) {
                $barang = Barang::where('Nama_Barang', $item->item)->first();
                if ($barang) {
                    $barang->update([
                        'Stok' => $barang->Stok + $item->jumlah
                    ]);
                }
            }
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

        return view('Approver.edit', compact('order', 'orderItems', 'items'));
    }

    public function update(Request $request, $noorder)
    {
        try {
            // Start database transaction
            DB::beginTransaction();

            try {
                $order = Order::where('noorder', $noorder)->first();
                if (!$order) {
                    throw new \Exception("Order not found");
                }

                // Get current order items before updating
                $currentItems = OrderItem::where('noorder', $noorder)->get();

                // Return stock for current items
                foreach ($currentItems as $currentItem) {
                    $barang = Barang::where('Nama_Barang', $currentItem->item)->first();
                    if ($barang) {
                        // Add back the stock
                        $barang->update([
                            'Stok' => $barang->Stok + $currentItem->jumlah
                        ]);
                    }
                }

                // Validate new items stock
                foreach ($request->items as $item) {
                    $barang = Barang::where('Nama_Barang', $item['nama'])->first();

                    if (!$barang) {
                        throw new \Exception("Item {$item['nama']} not found");
                    }

                    if ($barang->Stok < $item['jumlah']) {
                        throw new \Exception("Insufficient stock for {$item['nama']}. Available: {$barang->Stok}, Requested: {$item['jumlah']}");
                    }
                }

                // Update order details
                $order->update([
                    'acara' => $request->acara,
                    'tanggal_acara' => $request->tanggal_acara,
                    'tanggal_yang_diharapkan' => $request->tanggal_yang_diharapkan,
                ]);

                // Delete existing items
                OrderItem::where('noorder', $order->noorder)->delete();

                // Add new items and update stock
                foreach ($request->items as $item) {
                    $barang = Barang::where('Nama_Barang', $item['nama'])->first();

                    // Update stock
                    $barang->update([
                        'Stok' => $barang->Stok - $item['jumlah']
                    ]);

                    // Create new order item
                    OrderItem::create([
                        'noorder' => $order->noorder,
                        'item' => $item['nama'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }

                // If everything is successful, commit the transaction
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Order updated successfully'
                ]);
            } catch (\Exception $e) {
                // If there's an error, rollback the transaction
                DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
