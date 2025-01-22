<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class requestController extends Controller
{
    // public function dashboard(){
    //     return view('Requester.dashboard');
    // }

    public function request(){
        $items = Barang::all();

        // Kirim data ke blade file
        return view('Requester.request', compact('items'));
    }

    public function history(){
        $items = Barang::all();
        return view('Requester.myrequest', compact('items'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'acara' => 'required|string|max:255',
            'tanggal_acara' => 'required|date',
            'tanggal_yang_diharapkan' => 'required|date',
            'items' => 'required|array',
            'items.*.item' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        do{
            $noorder = Str::random(5);
        } while (Order::where('noorder', $noorder)->exists());

        // Create the order
        $order = Order::create([
            'noorder' => $noorder,
            'acara' => $validated['acara'],
            'tanggal_acara' => $validated['tanggal_acara'],
            'tanggal_yang_diharapkan' => $validated['tanggal_yang_diharapkan'],
            'status' => 'pending',
            'penerima' => $request->input('penerima', 'Anonymous'), // Default penerima
        ]);

        // Add items to the order
        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'noorder' => $order->noorder,
                'item' => $item['item'],
                'jumlah' => $item['jumlah'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully!',
            'data' => $order,
        ]);
    }
}
