<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Kategori</title>
    <link rel="stylesheet" href="{{ asset('css/admin/kategori.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="wrapper">
        <x-SidebarAdmin />

        <main class="main-content">
            <div class="container">
                <h2>Daftar Kategori</h2>

                <!-- Tombol Tambah Kategori -->
                <button id="openAddModal" class="submit-btn">Tambah Kategori</button>

                <!-- Tabel -->
                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $item)
                            <tr id="row-{{ $item->id }}">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->Kategori }}</td>
                                <td>
                                    <button class="delete-btn" data-id="{{ $item->id }}">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
    $(document).ready(function () {
        // Modal Tambah Kategori
        $('#openAddModal').on('click', function () {
            Swal.fire({
                title: 'Tambah Kategori',
                html: `
                    <form id="add-form">
                        <div class="form-group">
                            <label for="add-Kategori">Kategori:</label>
                            <input type="text" id="add-Kategori" name="Kategori" required class="swal2-input">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    let kategori = $('#add-Kategori').val();
                    if (!kategori) {
                        Swal.showValidationMessage('Kategori tidak boleh kosong!');
                        return false;
                    }
                    return kategori;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('kategori.create') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            Kategori: result.value,
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success')
                                    .then(() => location.reload());
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Gagal menambahkan kategori.', 'error');
                        }
                    });
                }
            });
        });

        // Hapus Kategori
        $('.delete-btn').on('click', function () {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Yakin?',
                text: 'Kategori akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/kategori/delete/${id}`,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success')
                                    .then(() => location.reload());
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Gagal menghapus kategori.', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
