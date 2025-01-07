<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="{{ asset('css/admin/addBarang.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <x-SidebarAdmin></x-SidebarAdmin>
        <main>
            <div class="container">
                <div class="card" style="margin-top: 20px;">
                    <button id="openAddModal" class="submit-btn">Tambah Barang</button>
                </div>
                
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeAddModal">&times;</span>
                        <h3>Tambah Barang</h3>
                        <form id="add-form" method="POST" action="{{ route('create.barang') }}">
                            @csrf
                            <div class="form-group">
                                <label for="add-id">ID Barang:</label>
                                <input type="text" id="add-id" name="id" required>
                            </div>
                            <div class="form-group">
                                <label for="add-Nama_Barang">Nama Barang:</label>
                                <input type="text" id="add-Nama_Barang" name="Nama_Barang" required>
                            </div>
                            <div class="form-group">
                                <label for="add-Kategori">Kategori:</label>
                                <input type="text" id="add-Kategori" name="Kategori" required>
                            </div>
                            <div class="form-group">
                                <label for="add-Deskripsi">Deskripsi:</label>
                                <input type="text" id="add-Deskripsi" name="Deskripsi" required>
                            </div>
                            <button type="submit" class="submit-btn">Tambah Barang</button>
                        </form>
                    </div>
                </div>
                

                <div class="card" style="margin-top: 40px;">
                    <h3>Daftar Barang</h3>
                    <table id="barang-table" border="1" cellspacing="0" cellpadding="10">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $barangs)
                                <tr id="row-{{ $barangs->id }}">
                                    <td>{{ $barangs->id }}</td>
                                    <td>{{ $barangs->Nama_Barang }}</td>
                                    <td>{{ $barangs->Kategori }}</td>
                                    <td>{{ $barangs->Deskripsi }}</td>
                                    <td>
                                        <button 
                                            type="button" 
                                            class="edit-btn" 
                                            data-id="{{ $barangs->id }}" 
                                            data-nama="{{ $barangs->Nama_Barang }}" 
                                            data-kategori="{{ $barangs->Kategori }}" 
                                            data-deskripsi="{{ $barangs->Deskripsi }}">
                                            Edit
                                        </button>
                                        <button 
                                            type="button" 
                                            class="delete-btn" 
                                            data-id="{{ $barangs->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <h3>Edit Barang</h3>
                        <form id="edit-form" method="POST" action="">
                            @csrf
                            <input type="hidden" id="edit-id" name="id">
                            <div class="form-group">
                                <label for="edit-Nama_Barang">Nama Barang:</label>
                                <input type="text" id="edit-Nama_Barang" name="Nama_Barang" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-Kategori">Kategori:</label>
                                <input type="text" id="edit-Kategori" name="Kategori" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-Deskripsi">Deskripsi:</label>
                                <input type="text" id="edit-Deskripsi" name="Deskripsi" required>
                            </div>
                            <button type="submit" class="submit-btn">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
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

        // Event untuk submit form tambah barang
        $('#add-form').on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.post("{{ route('create.barang') }}", formData, function (data) {
                if (data.success) {
                    alert('Barang berhasil ditambahkan!');
                    let newRow = `
                        <tr id="row-${data.data.id}">
                            <td>${data.data.id}</td>
                            <td>${data.data.Nama_Barang}</td>
                            <td>${data.data.Kategori}</td>
                            <td>${data.data.Deskripsi}</td>
                            <td>
                                <button type="button" class="edit-btn" data-id="${data.data.id}" data-nama="${data.data.Nama_Barang}" data-kategori="${data.data.Kategori}" data-deskripsi="${data.data.Deskripsi}">Edit</button>
                                <button type="button" class="delete-btn" data-id="${data.data.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#barang-table tbody').append(newRow);
                    attachDeleteEvent();
                    attachEditEvent();
                    $('#add-form')[0].reset();
                    $('#addModal').css('display', 'none');
                } else {
                    alert('Gagal menambahkan barang.');
                }
            }).fail(function (xhr, status, error) {
                alert('Terjadi kesalahan: ' + error);
            });
        });
    });

    $(document).ready(function () {
        // Fungsi untuk mengikat event tombol delete
        function attachDeleteEvent() {
            $('.delete-btn').off('click').on('click', function () {
                let id = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                    $.ajax({
                        url: "{{ route('delete.barang', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (response) {
                            if (response.success) {
                                $('#row-' + id).remove();
                                alert(response.message);
                            } else {
                                alert('Gagal menghapus barang.');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Terjadi kesalahan: ' + error);
                        }
                    });
                }
            });
        }

        // Fungsi untuk mengikat event tombol edit
        function attachEditEvent() {
            $('.edit-btn').off('click').on('click', function () {
                let id = $(this).data('id');
                let nama = $(this).data('nama');
                let kategori = $(this).data('kategori');
                let deskripsi = $(this).data('deskripsi');
                $('#edit-id').val(id);
                $('#edit-Nama_Barang').val(nama);
                $('#edit-Kategori').val(kategori);
                $('#edit-Deskripsi').val(deskripsi);
                let updateUrl = "{{ route('update.barang', ':id') }}".replace(':id', id);
                $('#edit-form').attr('action', updateUrl);
                $('#editModal').css('display', 'block');
            });
        }

        attachDeleteEvent();
        attachEditEvent();


        $('.close-btn').on('click', function () {
            $('#editModal').css('display', 'none');
        });

        $(window).on('click', function (e) {
            if ($(e.target).is('#editModal')) {
                $('#editModal').css('display', 'none');
            }
        });
    });
    </script>
</body>
</html>
