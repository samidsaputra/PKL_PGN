<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('role');
        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'Requester':
                return view('Requester.dashboard');
            case 'Approver':
                return view('Approver.dashboard');
            default:
                Auth::logout();
                return redirect('/')->withErrors('Role tidak dikenali');
        }
    }

    private function adminDashboard()
    {
        // Get top 5 ordered items
        $topItems = OrderItem::select('item', DB::raw('SUM(jumlah) as total_ordered'))
            ->groupBy('item')
            ->orderBy('total_ordered', 'DESC')
            ->limit(5)
            ->get();

        // Get orders by satuan kerja
        $topDivisions = User::select('users.satuan_kerja', DB::raw('COUNT(orders.noorder) as total_orders'))
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->where('users.role', '!=', 'admin')
            ->whereNotNull('users.satuan_kerja')
            ->groupBy('users.satuan_kerja')
            ->orderBy('total_orders', 'DESC')
            ->limit(5)
            ->get();

        // Count total items and orders
        $totalItems = Barang::count();
        $totalOrders = Order::count();

        // Get pending orders
        $pendingOrders = Order::where('status', 'pending')->count();

        // Get monthly orders
        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get low stock items
        $lowStockItems = Barang::where('stok', '<=', 50)
            ->orderBy('stok', 'asc')
            ->get();

        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get monthly trend
        $monthlyTrend = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalItems',
            'totalOrders',
            'pendingOrders',
            'monthlyOrders',
            'lowStockItems',
            'recentOrders',
            'monthlyTrend',
            'topItems',
            'topDivisions'
        ));
    }
}