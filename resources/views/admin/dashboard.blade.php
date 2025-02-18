<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/css/dashboard.css">
</head>
<body>
    <div class="wrapper">
        <x-SidebarAdmin />
        <main class="main-content">
            <div class="container">
                <h2>Dashboard</h2>
                
                <!-- Expanded Summary Cards -->
                <div class="summary-cards">
                    <div class="dashboard-card summary">
                        <h3>Total Items</h3>
                        <div class="count">{{ $totalItems }}</div>
                    </div>
                    <div class="dashboard-card summary">
                        <h3>Total Orders</h3>
                        <div class="count">{{ $totalOrders }}</div>
                    </div>
                    <div class="dashboard-card summary">
                        <h3>Pending Orders</h3>
                        <div class="count">{{ $pendingOrders }}</div>
                    </div>
                    <div class="dashboard-card summary">
                        <h3>This Month Orders</h3>
                        <div class="count">{{ $monthlyOrders }}</div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="dashboard-grid">
                    <!-- Left Column -->
                    <div class="dashboard-column">
                        <div class="dashboard-card">
                            <h3>Top 5 Most Ordered Items</h3>
                            <canvas id="topItemsChart"></canvas>
                        </div>

                        <div class="dashboard-card">
                            <h3>Top 5 Divisions by Order Frequency</h3>
                            <canvas id="topDivisionsChart"></canvas>
                        </div>

                        <!-- New Monthly Trend Chart -->
                        <div class="dashboard-card">
                            <h3>Monthly Orders Trend</h3>
                            <canvas id="monthlyTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="dashboard-column">
                        <!-- Low Stock Alert -->
                        @if($lowStockItems->count() > 0)
                        <div class="dashboard-card low-stock">
                            <h3>Low Stock Alert</h3>
                            <div class="table-responsive">
                                <table class="stock-table">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Current Stock</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->stok }}</td>
                                            <td>
                                                <span class="status-badge {{ $item->stok <= 20 ? 'critical' : 'warning' }}">
                                                    {{ $item->stok <= 20 ? 'Critical' : 'Low' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- New Recent Orders Section -->
                        <div class="dashboard-card">
                            <h3>Recent Orders</h3>
                            <div class="table-responsive">
                                <table class="stock-table">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Division</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->noorder }}</td>
                                            <td>{{ $order->user->satuan_kerja }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                                            <td>
                                                <span class="status-badge {{ strtolower($order->status) }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Chart for Top 5 Items
        const itemsChart = new Chart(
            document.getElementById('topItemsChart'),
            {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topItems->pluck('item')) !!},
                    datasets: [{
                        label: 'Total Orders',
                        data: {!! json_encode($topItems->pluck('total_ordered')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );

        // Chart for Top 5 Divisions
        const divisionsChart = new Chart(
            document.getElementById('topDivisionsChart'),
            {
                type: 'pie',
                data: {
                    labels: {!! json_encode($topDivisions->pluck('satuan_kerja')) !!},
                    datasets: [{
                        data: {!! json_encode($topDivisions->pluck('total_orders')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            }
        );

        // Monthly Trend Chart
        const monthlyTrendChart = new Chart(
            document.getElementById('monthlyTrendChart'),
            {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
                    datasets: [{
                        label: 'Monthly Orders',
                        data: {!! json_encode($monthlyTrend->pluck('total')) !!},
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );
    </script>
</body>
</html>