<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <x-SidebarAdmin></x-SidebarAdmin>
    
    <main class="dashboard">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Welcome To GasNlr</h1>
            <p class="dashboard-subtitle">We Serve the Sovenier</p>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <!-- Image Gallery Card -->
            <div class="dashboard-card large-card">
                <div class="image-gallery-container">
                    <div class="image-gallery">
                        <!-- Dummy items -->
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 1">
                            <div class="item-details">
                                <h3>Gantungan Kunci</h3>
                                <p>Rp 25.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 2">
                            <div class="item-details">
                                <h3>Kaos Custom</h3>
                                <p>Rp 85.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 3">
                            <div class="item-details">
                                <h3>Mug Custom</h3>
                                <p>Rp 35.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 4">
                            <div class="item-details">
                                <h3>Topi Custom</h3>
                                <p>Rp 45.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 5">
                            <div class="item-details">
                                <h3>Pin Badge</h3>
                                <p>Rp 15.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 6">
                            <div class="item-details">
                                <h3>Sticker Pack</h3>
                                <p>Rp 10.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Grid -->
            <div class="dashboard-grid">
                <!-- Left Card -->
                <div class="dashboard-card">
                    <div class="card-content">
                        <span class="card-label">Total Order</span>
                    </div>
                </div>

                <!-- Right Card -->
                <div class="dashboard-card">
                    <div class="card-content">
                        <span class="card-label">Item's Stock</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
=======
    <div class="wrapper">
        <x-SidebarAdmin />
        <main class="main-content">
            <div class="container">
                <h2>Dashboard</h2>

                <!-- Grafik Top 5 Barang yang Diminta -->
                <div class="card">
                    <h3>Top 5 Barang yang Paling Banyak Diminta</h3>
                    <canvas id="topBarangChart"></canvas>
                </div>

                <!-- Grafik Top 5 Divisi yang Meminta Barang -->
                <div class="card">
                    <h3>Top 5 Divisi yang Paling Banyak Meminta Barang</h3>
                    <canvas id="topDivisiChart"></canvas>
                </div>

                <!-- Tabel Stok Barang -->
                {{-- <div class="card">
                    <h3>Stok Barang</h3>
                    <table border="1" cellspacing="0" cellpadding="10">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokBarang as $barang)
                                <tr>
                                    <td>{{ $barang->Nama_Barang }}</td>
                                    <td>{{ $barang->Stok }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}

            </div>
        </main>
    </div>

    <script>
        // Data untuk Grafik Top Barang
        var topBarangLabels = {!! json_encode($topBarang->pluck('barang.Nama_Barang')) !!};
        var topBarangData = {!! json_encode($topBarang->pluck('jumlah_pesanan')) !!};

        var ctxBarang = document.getElementById('topBarangChart').getContext('2d');
        new Chart(ctxBarang, {
            type: 'bar',
            data: {
                labels: topBarangLabels,
                datasets: [{
                    label: 'Jumlah Diminta',
                    data: topBarangData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
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
        });

        // Data untuk Grafik Top Divisi
        var topDivisiLabels = {!! json_encode($topDivisi->pluck('divisi.nama')) !!};
        var topDivisiData = {!! json_encode($topDivisi->pluck('jumlah_pesanan')) !!};

        var ctxDivisi = document.getElementById('topDivisiChart').getContext('2d');
        new Chart(ctxDivisi, {
            type: 'bar',
            data: {
                labels: topDivisiLabels,
                datasets: [{
                    label: 'Jumlah Permintaan',
                    data: topDivisiData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
        });
    </script>
</body>
</html>
