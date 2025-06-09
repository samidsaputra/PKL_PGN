<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="{{ asset('css/Requester/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <x-sidebar></x-sidebar>
    <main class="main-content">
        <div class="dashboard-header">Welcome to Gasnir</div>
        <div class="container">
            <h3>New Arrivals</h3>
            <div class="grid">
                @foreach($items->sortByDesc('created_at')->take(4) as $item)
                <div class="product-card" onclick="showConfirmation('{{ $item->id }}', '{{ $item->Nama_Barang }}', '{{ $item->Kategori }}', {{ $item->Stok }})">
                    <div class="product-image-container">
                        <img src="{{ url('storage/'.$item->Gambar) }}" alt="{{ $item->Nama_Barang }}">
                    </div>
                    <div class="product-details">
                        <h3>{{ $item->Nama_Barang }}</h3>
                        <p class="product-category">{{ $item->Kategori }}</p>
                        <span class="product-stock {{ $item->Stok <= 5 ? ($item->Stok == 0 ? 'out-of-stock' : 'low-stock') : '' }}">
                        @if($item->Stok == 0)
                            Out of Stock
                        @elseif($item->Stok <= 5)
                            Low Stock: {{ $item->Stok }}
                        @else
                            Stock: {{ $item->Stok }}
                        @endif
                        </span>
                    </div>
                </div>
                @endforeach
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
        </div>
    </main>
</body>

</html>