<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
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
