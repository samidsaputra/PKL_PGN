<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function request()
    {
        $items = Barang::all();
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
                'tanggal_yang_diharapkan' => 'required|date',
                'items' => 'required|array',
                'items.*.nama' => 'required|string',
                'items.*.jumlah' => 'required|integer|min:1',
            ])->validate();

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
                OrderItem::create([
                    'noorder' => $order->noorder,
                    'item' => $item['nama'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully!',
                'data' => $order,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function edit($noorder)
    {
        $order = Order::where('noorder', $noorder)->first();
        $orderItems = OrderItem::where('noorder', $order->noorder)->get();
        $items = Barang::all(); // Get all available items
    
        return view('Requester.edit',compact('order','orderItems' ,'items'));
    }
    
    public function update(Request $request, $noorder)
    {
        $order = Order::where('noorder', $noorder)->first();
        
        $order->update([
            'acara' => $request->acara,
            'tanggal_acara' => $request->tanggal_acara,
            'tanggal_yang_diharapkan' => $request->tanggal_yang_diharapkan,
            'status' => 'Pending'
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