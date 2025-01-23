<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        display: flex;
        height: 100vh;
        background-color: var(--background-color);
    }

    main {
        padding: 2rem;
        padding-left: 4.5rem;
        flex-grow: 1;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px;
        position: relative;
    }

    /* Header */
    h1 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Alerts */
    .alert {
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        font-weight: 600;
        text-align: center;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* Table */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th, .table td {
        text-align: left;
        padding: 12px 15px;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Buttons */
    /* Button Styles */
    .btn-open-modal {
        background-color: #007bff; /* Warna biru */
        color: #fff; /* Warna teks putih */
        border: none; /* Hilangkan border */
        border-radius: 4px; /* Membuat sudut melengkung */
        padding: 8px 12px; /* Ruang dalam tombol */
        font-size: 14px; /* Ukuran teks */
        font-weight: 600; /* Tebal teks */
        cursor: pointer; /* Pointer saat hover */
        transition: background-color 0.3s ease, transform 0.2s ease; /* Animasi transisi */
    }

    .btn-open-modal:hover {
        background-color: #0056b3; /* Warna lebih gelap saat hover */
        transform: scale(1.05); /* Perbesar sedikit */
    }

    .btn-open-modal:active {
        background-color: #003f7f; /* Warna lebih gelap saat ditekan */
        transform: scale(0.95); /* Kecilkan sedikit */
    }

    .btn-open-modal:focus {
        outline: none; /* Hilangkan outline default */
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5); /* Bayangan fokus */
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 12px;
        text-transform: uppercase;
    }

    .badge.bg-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    .modal {
    display: none;
    position: fixed;
    padding: 0 100px;
    top: 0;
    left: 0;
    width: 80%;
    height: 80%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 50%;
        position: relative;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .modal-body th, .modal-body td {
        text-align: left;
        padding: 8px;
        border: 1px solid #ddd;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
    }


    </style>
</head>
<body >
    <x-sidebar></x-sidebar>
    <main>
        <div class="container">
            <h1>Order Table</h1>
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
                        <th>Tanggal Yang Diharapkan</th>
                        <th>Status</th>
                        <th>Penerima</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Tanggal Revisi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @if(strtolower($order->status) !== 'setuju')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->noorder }}</td>
                                <td>{{ $order->acara }}</td>
                                <td>{{ $order->tanggal_acara }}</td>
                                <td>{{ $order->tanggal_yang_diharapkan }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>{{ $order->penerima }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{{ $order->updated_at }}</td>
                                <th>
                                    <button class="btn-open-modal" data-order="{{ $order->noorder }}" data-acara="{{ $order->acara }}" data-tanggal-acara="{{ $order->tanggal_acara }}" data-tanggal-expected="{{ $order->tanggal_yang_diharapkan }}" data-penerima="{{ $order->penerima}}">Open</button>
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
            <div class="modal-footer">
                <button class="btn btn-secondary">Revisi</button>
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-danger">Tolak</button>
                <button class="btn btn-success">Setuju</button>
            </div>
        </div>
    </div>
    <!-- Modal -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('orderModal');
        const modalNoOrder = document.getElementById('modalNoOrder');
        const modalAcara = document.getElementById('modalAcara');
        const modalTanggalAcara = document.getElementById('modalTanggalAcara');
        const modalTanggalYangDiharapkan = document.getElementById('modalTanggalYangDiharapkan');
        const modalPenerima = document.getElementById('modalPenerima')
        const modalClose = document.getElementById('closeModal');

        // Handle Open Modal
        document.querySelectorAll('.btn-open-modal').forEach(button => {
            button.addEventListener('click', () => {
                modalNoOrder.textContent = button.getAttribute('data-order');
                modalAcara.textContent = button.getAttribute('data-acara');
                modalTanggalAcara.textContent = button.getAttribute('data-tanggal-acara');
                modalTanggalYangDiharapkan.textContent = button.getAttribute('data-tanggal-expected');
                modalPenerima.textContent = button.getAttribute('data-penerima');
                modal.style.display = 'flex';
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
