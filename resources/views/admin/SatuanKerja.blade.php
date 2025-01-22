<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/SatuanKerja.css') }}">
    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add meta tag for CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-SidebarAdmin></x-SidebarAdmin>
    <main>
        <div class="register">
            <div class="form-section">
                <h2>Create Satuan Kerja</h2>
            
                <form id="create-form" action="{{ route('createSatker') }}" method="POST">
                    @csrf
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Enter Satuan Kerja" required>
                    <input type="text" name="id" value="{{ old('id') }}" placeholder="Enter ID" required>
                    <input type="text" name="perusahaan" value="{{ old('perusahaan') }}" placeholder="Enter Perusahaan" required>
                    <input type="submit" value="Input">
                </form>
            </div>
        </div>
        <div class="table-user">
            <h1>Satuan Kerja</h1>
            <table id="userTable">
                <thead>
                    <tr>
                        <th>Satuan Kerja</th>
                        <th>ID</th>
                        <th>Perusahaan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($satuan_kerja as $satker)
                    <tr>
                        <td>{{ $satker->nama }}</td>
                        <td>{{ $satker->id }}</td>
                        <td>{{ $satker->perusahaan }}</td>
                        <td>
                            <button 
                                type="button" 
                                class="edit-btn" 
                                data-nama="{{ $satker->nama }}" 
                                data-perusahaan="{{ $satker->perusahaan }}"
                                data-id="{{ $satker->id}}" >
                                Edit
                            </button>
                            <button 
                                type="button" 
                                class="delete-btn" 
                                data-nama="{{ $satker->nama }}">
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
                <h3>Edit Satuan Kerja</h3>
                <form id="edit-form" method="PUT">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-nama" name="nama">
                    <div class="form-group">
                        <label for="edit-id">ID:</label>
                        <input type="text" id="edit-id" name="id" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-perusahaan">Perusahaan:</label>
                        <input type="text" id="edit-perusahaan" name="perusahaan" required>
                    </div>
                    <button type="submit" class="submit-btn">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </main>
        <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Create form submit handler
            $('#create-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Satuan Kerja berhasil ditambahkan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat menambahkan Satuan Kerja'
                        });
                    }
                });
            });

            // Edit button click handler
            $('.edit-btn').on('click', function() {
                const nama = $(this).data('nama');
                const id = $(this).data('id');
                const perusahaan = $(this).data('perusahaan');

                $('#edit-nama').val(nama);
                $('#edit-id').val(id);
                $('#edit-perusahaan').val(perusahaan);

                const updateUrl = "{{ route('update.satker', ':nama') }}".replace(':nama', encodeURIComponent(nama));
                $('#edit-form').attr('action', updateUrl);

                $('#editModal').show();
            });

            // Edit form submit handler
            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: form.serialize(),
                    success: function(response) {
                        $('#editModal').hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Satuan Kerja berhasil diperbarui',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memperbarui Satuan Kerja'
                        });
                    }
                });
            });

            // Delete button click handler
            $('.delete-btn').on('click', function() {
                const nama = $(this).data('nama');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data Satuan Kerja akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.satker', ':nama') }}".replace(':nama', encodeURIComponent(nama)),
                            type: 'DELETE',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Gagal menghapus Satuan Kerja'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat menghapus Satuan Kerja'
                                });
                            }
                        });
                    }
                });
            });

            // Close modal handlers remain the same
            $('.close-btn, .modal').on('click', function(e) {
                if (e.target === this) {
                    $('#editModal').hide();
                }
            });
        });
        </script>
    </body>
</html>