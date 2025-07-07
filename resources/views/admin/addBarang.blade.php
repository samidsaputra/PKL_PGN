<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="{{ asset('css/admin/addBarang.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="wrapper">
        <x-SidebarAdmin />
        <main class="main-content">
            <div class="container">
                <div class="card" style="margin-top: 20px;">
                    <button id="openAddModal" class="submit-btn">Tambah Barang</button>
                </div>
                <!-- Modal Tambah Barang -->
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeAddModal">&times;</span>
                        <h3>Tambah Barang</h3>
                        <form id="add-form" method="POST" action="{{ route('create.barang') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="add-Nama_Barang">Nama Barang:</label>
                                <input type="text" id="add-Nama_Barang" name="Nama_Barang" required>
                            </div>
                            <div class="form-group">
                                <label for="add-Kategori">Kategori Barang:</label>
                                <select id="add-Kategori" name="Kategori_Id" required>
                                    @foreach ($kategori as $kategoris)
                                        <option value="{{ $kategoris->id }}">{{ $kategoris->Kategori }}</option>
                                    @endforeach
                                </select>
                            </div>  
                            <div class="form-group">
                                <label for="add-Stok">Stok:</label>
                                <input type="text" id="add-Stok" name="Stok" required>
                            </div>                          
                            <div class="form-group">
                                <label for="add-Deskripsi">Deskripsi:</label>
                                <input type="text" id="add-Deskripsi" name="Deskripsi" required>
                            </div>
                            <div class="form-group">
                                <label for="add-Gambar">Gambar Barang:</label>
                                <input type="file" id="add-Gambar" name="Gambar" accept="image/*">
                            </div>
                            <button type="submit" class="submit-btn">Tambah Barang</button>
                        </form>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="card" style="margin-top: 40px;">
                    <h3>Daftar Barang</h3>
                    <div class="card" style="margin-top: 20px; display: flex;">
                        <p>Search:</p>
                        <input type="text" id="search-bar" placeholder="Cari barang..." class="search-input">
                    </div>
                    <table id="barang-table" border="1" cellspacing="0" cellpadding="10">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Kategori Barang</th>
                                <th>Stok</th>
                                <th>Deskripsi</th>
                                <th>Gambar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $barangs)
                                <tr id="row-{{ $barangs->id }}">
                                    <td>{{ $barangs->id }}</td>
                                    <td>{{ $barangs->Nama_Barang }}</td>
                                    <td>{{ $barangs->Kategori }}</td> <!-- Mengakses nama kategori -->
                                    <td>{{ $barangs->Stok }}</td>
                                    <td>{{ $barangs->Deskripsi }}</td>
                                    <td>
                                        @if($barangs->Gambar)
                                            <img src="{{ url('storage/'.$barangs->Gambar) }}" alt="Gambar Barang" style="width: 100px; height: auto;">
                                        @else
                                            Tidak ada gambar
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="edit-btn" data-id="{{ $barangs->id }}" data-nama="{{ $barangs->Nama_Barang }}" data-kategori="{{ $barangs->Kategori }}" data-Stok="{{ $barangs->Stok }}" data-deskripsi="{{ $barangs->Deskripsi }}">
                                            Edit
                                        </button>
                                        <button class="delete-btn" data-id="{{ $barangs->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script>
    $(document).ready(function () {
        // Event untuk membuka modal tambah barang
        $('#openAddModal').on('click', function () {
            $('#addModal').css('display', 'block');
        });

        // Event untuk menutup modal tambah barang
        $('#closeAddModal').on('click', function () {
            $('#addModal').css('display', 'none');
        });

        $(window).on('click', function (e) {
            if ($(e.target).is('#addModal')) {
                $('#addModal').css('display', 'none');
            }
        });
        // Tambah Barang
        $('#add-form').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this); // Gunakan FormData untuk mengirim file
            $.ajax({
                url: "{{ route('create.barang') }}",
                type: "POST",
                data: formData,
                processData: false,  // Jangan memproses data secara otomatis
                contentType: false,  // Jangan atur Content-Type secara otomatis
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', response.message || 'Gagal menambahkan barang.', 'error');
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menambahkan barang.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                }
            });
        });
        $(document).on('click', '.edit-btn', function () {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let kategoriId = $(this).data('kategori');
            let stok = $(this).data('stok')
            let deskripsi = $(this).data('deskripsi');

            Swal.fire({
                title: 'Edit Barang',
                html: `
                    <input type="hidden" id="edit-id" value="${id}">
                    <div class="form-group">
                        <label>Nama Barang:</label>
                        <input type="text" id="edit-Nama_Barang" class="swal2-input" value="${nama}">
                    </div>
                    <div class="form-group">
                        <label>Kategori:</label>
                        <select id="edit-Kategori" class="swal2-input">
                            @foreach ($kategori as $kategoris)
                                <option value="{{ $kategoris->id }}" ${kategoriId == {{ $kategoris->id }} ? 'selected' : ''}>{{ $kategoris->Kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Stok:</label>
                        <input type="text" id="edit-Stok" class="swal2-input" value="${stok}">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi:</label>
                        <input type="text" id="edit-Deskripsi" class="swal2-input" value="${deskripsi}">
                    </div>
                `,
                confirmButtonText: 'Simpan',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    return {
                        Nama_Barang: document.getElementById('edit-Nama_Barang').value,
                        Kategori_Id: document.getElementById('edit-Kategori').value,  // Pastikan mengirimkan Kategori_Id
                        Stok: document.getElementById('edit-Stok').value,
                        Deskripsi: document.getElementById('edit-Deskripsi').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('update.barang', ':id') }}".replace(':id', encodeURIComponent(id)),
                        type: 'PUT',
                        data: {
                            _token: "{{ csrf_token() }}",
                            Nama_Barang: result.value.Nama_Barang,
                            Kategori_Id: result.value.Kategori_Id,  // Kirim Kategori_Id
                            Stok: result.value.Stok,
                            Deskripsi: result.value.Deskripsi
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', response.message || 'Gagal memperbarui barang.', 'error');
                            }
                        },
                        error: function (xhr) {
                            let errorMessage = 'Terjadi kesalahan saat memperbarui barang.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message; // Menampilkan pesan error dari backend
                            }
                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
            });
        });


        // Hapus Barang
        $(document).on('click', '.delete-btn', function () {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Yakin?',
                text: 'Barang akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.barang', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Terhapus!', response.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', response.message || 'Gagal menghapus barang.', 'error');
                            }
                        },
                        error: function (xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menghapus barang.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message; // Menampilkan pesan error dari backend
                            }
                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
            });
        });
    });
    $(document).ready(function () {
        // Event untuk melakukan pencarian
        $('#search-bar').on('keyup', function () {
            let searchTerm = $(this).val().toLowerCase();
            $('#barang-table tbody tr').each(function () {
                let rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
    </script>
</body>
</html>
