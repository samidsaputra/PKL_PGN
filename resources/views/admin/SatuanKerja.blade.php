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
                    <input type="text" name="contact" value="{{ old('contact') }}" placeholder="Enter No Telfon" required>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
                    <input type="text" name="PIC" value="{{ old('PIC') }}" placeholder="Enter Nama PIC" required>
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
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Person In Charge</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($satuan_kerja as $satker)
                    <tr>
                        <td>{{ $satker->nama }}</td>
                        <td>{{ $satker->contact }}</td>
                        <td>{{ $satker->email }}</td>
                        <td>{{ $satker->PIC }}</td>
                        <td>
                            <button 
                                type="button" 
                                class="edit-btn" 
                                data-nama="{{ $satker->nama }}" 
                                data-contact="{{ $satker->contact }}" 
                                data-email="{{ $satker->email }}" 
                                data-pic="{{ $satker->PIC }}">
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
                <form id="edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-nama" name="nama">
                    <div class="form-group">
                        <label for="edit-contact">Contact:</label>
                        <input type="text" id="edit-contact" name="contact" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Email:</label>
                        <input type="email" id="edit-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-PIC">PIC:</label>
                        <input type="text" id="edit-PIC" name="PIC" required>
                    </div>
                    <button type="submit" class="submit-btn">Simpan Perubahan</button>
                </form>
            </div>
        </div>


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
                const contact = $(this).data('contact');
                const email = $(this).data('email');
                const pic = $(this).data('pic');

                $('#edit-nama').val(nama);
                $('#edit-contact').val(contact);
                $('#edit-email').val(email);
                $('#edit-PIC').val(pic);

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
                    type: 'POST',
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