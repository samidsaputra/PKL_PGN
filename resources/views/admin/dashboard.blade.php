<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>
<body>
    <x-SidebarAdmin />
    <main class="main-content">
        <div class="dashboard-header">Welcome to Gasnir</div>
        <div class="container">

            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- Left Column Stats -->
                <div class="stats-section">
                    <div class="stat-item">
                        <div class="stat-title">Total Items</div>
                        <div class="stat-value">{{ $totalItems }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-title">Total Orders</div>
                        <div class="stat-value">{{ $totalOrders }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-title">Pending Orders</div>
                        <div class="stat-value">{{ $pendingOrders }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-title">This Month Orders</div>
                        <div class="stat-value">{{ $monthlyOrders }}</div>
                    </div>
                </div>
                
                <!-- Right Column Stats -->
                <div class="stats-section">
                    @if($lowStockItems->count() > 0)
                    <h3 class="section-title">Low Stock Alert</h3>
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
                                    <td>{{ $item->Nama_Barang }}</td>
                                    <td>{{ $item->Stok }}</td>
                                    <td>
                                        <span class="status-badge {{ $item->Stok <= 20 ? 'status-critical' : 'status-warning' }}">
                                            {{ $item->Stok <= 20 ? 'Critical' : 'Low' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="stats-section-chart">
                    <div class="dashboard-card">
                        <h3>Top 5 Most Ordered Items</h3>
                        <canvas id="topItemsChart"></canvas>
                    </div>
                </div>
                <div class="stats-section-chart">
                    <div class="dashboard-card">
                        <h3>Top 5 Divisions by Order Frequency</h3>
                        <canvas id="topDivisionsChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Basic slider functionality
        document.addEventListener('DOMContentLoaded', function() {
            const productRow = document.querySelector('.product-row');
            const prevButton = document.querySelector('.prev-arrow');
            const nextButton = document.querySelector('.next-arrow');
            
            let scrollAmount = 0;
            const cardWidth = 270; // width + gap

            nextButton?.addEventListener('click', function() {
                scrollAmount += cardWidth;
                if(scrollAmount > productRow.scrollWidth - productRow.clientWidth) {
                    scrollAmount = 0;
                }
                productRow.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            prevButton?.addEventListener('click', function() {
                scrollAmount -= cardWidth;
                if(scrollAmount < 0) {
                    scrollAmount = productRow.scrollWidth - productRow.clientWidth;
                }
                productRow.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        });

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
    </script>
</body>
</html>