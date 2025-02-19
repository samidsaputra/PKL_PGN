<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Approver/approved.css') }}">
</head>
<body >
    <x-sidebar></x-sidebar>
    <main>
        <div class=table-wrapper>
            <h1>Order History</h1>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Order</th>
                        <th>Acara</th>
                        <th>Tanggal Acara</th>
                        <th>Status</th>
                        <th>Penerima</th>
                        <th>Terakhir Diubah</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($orders as $order)
                        @if(strtolower($order->status) === 'setuju')
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $order->noorder }}</td>
                                <td>{{ $order->acara }}</td>
                                <td>{{ $order->tanggal_acara }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>{{ $order->user->name }}</td>  <!-- Menampilkan nama user -->
                                <td>{{ $order->updated_at }}</td>
                                <th>
                                    <button class="btn-open-modal" 
                                        data-order="{{ $order->noorder }}" 
                                        data-acara="{{ $order->acara }}" 
                                        data-tanggal-acara="{{ $order->tanggal_acara }}" 
                                        data-tanggal-expected="{{ $order->tanggal_yang_diharapkan }}" 
                                        data-penerima="{{ $order->user->name }}">Open</button>
                                </th>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <div class="modal" id="orderModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>No Order: <span id="modalNoOrder"></span></h2>
                <button class="btn-close" id="closeModal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Acara:</strong> <span id="modalAcara"></span></p>
                <p><strong>Tanggal Pelaksanaan:</strong> <span id="modalTanggalAcara"></span></p>
                <p><strong>Tanggal Yang Diharapkan:</strong> <span id="modalTanggalYangDiharapkan"></span></p>
                <p><strong>Nama Pemesan:</strong> <span id="modalPenerima"></span></p>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody id="modalItems">
                        <!-- Item details will be injected dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('orderModal');
        const modalNoOrder = document.getElementById('modalNoOrder');
        const modalAcara = document.getElementById('modalAcara');
        const modalTanggalAcara = document.getElementById('modalTanggalAcara');
        const modalTanggalYangDiharapkan = document.getElementById('modalTanggalYangDiharapkan');
        const modalPenerima = document.getElementById('modalPenerima');
        const modalItems = document.getElementById('modalItems');
        const modalClose = document.getElementById('closeModal');

        // Fungsi untuk mengambil data order dari server
        async function fetchOrderDetails(noorder) {
            try {
                const response = await fetch(`/orders/${noorder}`);
                if (!response.ok) {
                    throw new Error('Order tidak ditemukan');
                }
                const data = await response.json();

                // Set data di dalam modal
                modalNoOrder.textContent = data.noorder;
                modalAcara.textContent = data.acara;
                modalTanggalAcara.textContent = data.tanggal_acara;
                modalTanggalYangDiharapkan.textContent = data.tanggal_yang_diharapkan;
                modalPenerima.textContent = data.penerima;

                // Kosongkan tabel item sebelum menambahkan data baru
                modalItems.innerHTML = '';

                // Tambahkan data item ke dalam tabel
                data.items.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                    `;
                    modalItems.appendChild(row);
                });

                // Tampilkan modal
                modal.style.display = 'flex';
            } catch (error) {
                alert(error.message);
            }
        }

        // Handle Open Modal
        document.querySelectorAll('.btn-open-modal').forEach(button => {
            button.addEventListener('click', () => {
                const noorder = button.getAttribute('data-order');
                fetchOrderDetails(noorder);
            });
        });

        // Handle Close Modal
        modalClose.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Close Modal on Background Click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>