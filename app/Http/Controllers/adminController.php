<?php

namespace App\Http\Controllers;
use App\Models\Order;



use Illuminate\Http\Request;

class adminController extends Controller
{
    public function pesanan(){
        $orders = Order::all();
        
        return view('admin.Pesanan', compact('orders'));
    }
}
