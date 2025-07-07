<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/Requester/myrequest.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
                        <th>Tanggal Pemesanan</th>
                        <th>Tanggal Revisi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($orders as $order)
                        @if($order->user_id === auth()->user()->id)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $order->noorder }}</td>
                            <td>{{ $order->acara }}</td>
                            <td>{{ $order->tanggal_acara }}</td>
                            <td>{{ $order->tanggal_yang_diharapkan }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->updated_at }}</td>
                            <th>
                                <button class="btn-open-modal" data-order="{{ $order->noorder }}" data-acara="{{ $order->acara }}" data-tanggal-acara="{{ $order->tanggal_acara }}" data-tanggal-expected="{{ $order->tanggal_yang_diharapkan }}" data-status="{{ $order->status }}">Open</button>
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
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
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
                <div class="modal-footer" id="modalFooter">
                    <!-- Edit button will be injected here if status is Revisi -->
                </div>
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
        const modalItems = document.getElementById('modalItems');
        const modalClose = document.getElementById('closeModal');
        const modalStatus = document.getElementById('modalStatus');
        const modalFooter = document.getElementById('modalFooter');

        // Fungsi untuk mengambil data order dari server
        async function fetchOrderDetails(noorder) {
            try {
                const response = await fetch(`/req/myrequest/detail/${noorder}`);
                if (!response.ok) {
                    throw new Error('Order tidak ditemukan');
                }
                const data = await response.json();

                // Set data di dalam modal
                modalNoOrder.textContent = data.noorder;
                modalAcara.textContent = data.acara;
                modalTanggalAcara.textContent = data.tanggal_acara;
                modalTanggalYangDiharapkan.textContent = data.tanggal_yang_diharapkan;
                modalStatus.textContent = data.status;

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

                // Handle edit button visibility
                modalFooter.innerHTML = '';
                if (data.status === 'Revisi') {
                    const editButton = document.createElement('a');
                    const noorder = modalNoOrder.textContent;
                    editButton.href = `/req/myrequest/${noorder}/edit`;
                    editButton.className = 'btn btn-warning';
                    editButton.textContent = 'Edit Order';
                    modalFooter.appendChild(editButton);
                }
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

