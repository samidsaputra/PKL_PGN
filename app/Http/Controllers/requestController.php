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
        $items = Barang::all();
        return view('Requester.myrequest', compact('items'));
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
                'items.*.id' => 'required|string|exists:barang,id',
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
                'status' => 'pending',
                'penerima' => $request->user()->name ?? 'Anonymous',
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'noorder' => $order->noorder,
                    'item' => $item['id'],
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
}