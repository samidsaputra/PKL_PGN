<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <x-sidebar></x-sidebar>
    <main class="main-content">
        <div class="dashboard-header">Welcome to Gasnir</div>
        <div class="container">
            <div class="product-slider">
                <h3>New Arrivals</h3>
                <div class="product-row">
                @for($i = 0; $i < 4; $i++)
                    @foreach($items as $item)
                    <div class="product-card" onclick="addToCart('{{ $item->id }}', '{{ $item->Nama_Barang }}', '{{ $item->Kategori }}')">
                        <div class="product-image">
                            <img src="{{ url('storage/'.$item->Gambar) }}">
                        </div>
                        <div class="product-name">{{ $item->Nama_Barang }}</div>
                        <div class="product-price">{{ $item->Kategori }}</div>
                        <div class="rating">Stock: {{ $item->Stok }}</div>
                </div>
                @endforeach
                @endfor

            </div>
            <div class="slider-controls">
                <button class="slider-arrow prev-arrow">←</button>
                <button class="slider-arrow next-arrow">→</button>
            </div>
        </div>
        <div class="stat-grid">
            <a class="stats-section" href="{{ route('request.history')}}">
                <div class="stat-item">
                    <div class="stat-title">Revisi:</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Order</th>
                                <th>Acara</th>
                                <th>Note Revisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($revisi as $order)
                            @if($order->user_id === auth()->user()->id)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $order->noorder }}</td>
                                <td>{{ $order->acara }}</td>
                                <td>{{ $order->revision_note }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productRow = document.querySelector('.product-row');
            const prevButton = document.querySelector('.prev-arrow');
            const nextButton = document.querySelector('.next-arrow');

            let scrollAmount = 0;
            const cardWidth = 270; // width + gap

            nextButton?.addEventListener('click', function() {
                scrollAmount += cardWidth;
                if (scrollAmount > productRow.scrollWidth - productRow.clientWidth) {
                    scrollAmount = 0;
                }
                productRow.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            prevButton?.addEventListener('click', function() {
                scrollAmount -= cardWidth;
                if (scrollAmount < 0) {
                    scrollAmount = productRow.scrollWidth - productRow.clientWidth;
                }
                productRow.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>