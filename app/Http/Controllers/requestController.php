<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function request()
    {
        $items = Barang::orderBy('Nama_Barang', 'asc')->get();
        return view('Requester.request', compact('items'));
    }


    public function history()
    {
        $orders = Order::all();
        $order_items = OrderItem::all();
        return view('Requester.myrequest', compact('orders', 'order_items'));
    }

    public function show($noorder)
    {
        // Cari order berdasarkan noorder
        $order = Order::with(['items' => function ($query) {
        $query->orderBy('created_at', 'asc');
        }])->where('noorder', $noorder)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Format data untuk dikirim ke frontend
        $data = [
            'noorder' => $order->noorder,
            'acara' => $order->acara,
            'tanggal_acara' => $order->tanggal_acara,
            'tanggal_yang_diharapkan' => $order->tanggal_yang_diharapkan,
            'status' => $order->status,
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

    public function checkout(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $validated = validator($data, [
                'acara' => 'required|string|max:255',
                'tanggal_acara' => 'required|date',
                'tanggal_yang_diharapkan' => 'required|date|after_or_equal:today|before_or_equal:tanggal_acara',
                'items' => 'required|array',
                'items.*.nama' => 'required|string',
                'items.*.id' => 'required|string',
                'items.*.jumlah' => 'required|integer|min:1',
            ])->validate();

            // Start database transaction
            DB::beginTransaction();

            try {
                // Pre-validate all items stock before processing
                foreach ($validated['items'] as $item) {
                    $barang = Barang::where('Nama_Barang', $item['nama'])->first();

                    if (!$barang) {
                        throw new \Exception("Item {$item['nama']} not found");
                    }

                    if ($barang->Stok < $item['jumlah']) {
                        throw new \Exception("Insufficient stock for {$item['nama']}. Available: {$barang->Stok}, Requested: {$item['jumlah']}");
                    }
                }

                do {
                    $noorder = Str::random(5);
                } while (Order::where('noorder', $noorder)->exists());

                $order = Order::create([
                    'noorder' => $noorder,
                    'acara' => $validated['acara'],
                    'tanggal_acara' => $validated['tanggal_acara'],
                    'tanggal_yang_diharapkan' => $validated['tanggal_yang_diharapkan'],
                    'status' => 'Pending',
                    'user_id' => $request->user()->id ?? 'Anonymous',
                    'revision_note' => '-',
                ]);

                foreach ($validated['items'] as $item) {
                    // Find the barang
                    $barang = Barang::where('Nama_Barang', $item['nama'])->first();

                    if (!$barang) {
                        throw new \Exception("Item {$item['nama']} not found");
                    }

                    // Check if enough stock is available
                    if ($barang->Stok < $item['jumlah']) {
                        throw new \Exception("Insufficient stock for {$item['nama']}");
                    }

                    // Update stock
                    $barang->update([
                        'Stok' => $barang->Stok - $item['jumlah']
                    ]);

                    // Create order item
                    OrderItem::create([
                        'noorder' => $order->noorder,
                        'item' => $item['nama'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }

                // If everything is successful, commit the transaction
                DB::commit();
                // return redirect()->back();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order created successfully!',
                    'data' => $order,
                ]);
            } catch (\Exception $e) {
                // If there's an error, rollback the transaction
                DB::rollback();
                throw $e;
                
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (strpos($message, 'tanggal_yang_diharapkan') !== false) {
                $message = 'Tanggal yang diharapkan harus berada di masa sekarang atau masa depan dan tidak boleh melewati tanggal acara.';
            }
            return response()->json([
                'status' => 'error',
                'message' => $message
            ], 422);
        }
    }

    public function edit($noorder)
    {
        $order = Order::where('noorder', $noorder)->first();
        $orderItems = OrderItem::where('noorder', $order->noorder)->get();
        $items = Barang::all(); // Get all available items

        return view('Requester.edit', compact('order', 'orderItems', 'items'));
    }

    public function update(Request $request, $noorder)
    {
        $order = Order::where('noorder', $noorder)->first();

        $order->update([
            'acara' => $request->acara,
            'tanggal_acara' => $request->tanggal_acara,
            'tanggal_yang_diharapkan' => $request->tanggal_yang_diharapkan,
            'status' => 'Sudah Revisi'
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
