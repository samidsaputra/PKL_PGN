<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/UserRegistration.css') }}">
</head>

<body>
    <x-SidebarAdmin></x-SidebarAdmin>
    <main>
        <div class="table-user">
            <div class="table-user-header">
                <h1>User List</h1>
                <button class="make-user-btn" onclick="openModal()">Make User</button>
            </div>
            
            <!-- Modal -->
            <div id="userModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <div class="form-section">
                        <h2>Create Account</h2>

                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form id="user-form" action="{{ route('register.store') }}" method="POST">
                            @csrf
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Name" required>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
                            <select name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Requester" {{ old('role') == 'Requester' ? 'selected' : '' }}>Requester</option>
                                <option value="Approver" {{ old('role') == 'Approver' ? 'selected' : '' }}>Approver</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <select name="satuan_kerja" required>
                                <option value="" disabled selected>Select Satuan Kerja</option>
                                @foreach($satuanKerja as $satker)
                                <option value="{{ $satker->nama }}" {{ old('satuan_kerja') == $satker->nama ? 'selected' : '' }}>
                                    {{ $satker->nama }}
                                </option>
                                @endforeach
                            </select>
                            <input type="password" name="password" placeholder="Enter Password" required>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                            <input type="submit" value="Create User">
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-wrapper">
                <table id="userTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Satuan Kerja</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->satuan_kerja}}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('users.resetDefaultPassword', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reset the password?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning">Reset Password</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // Modal functions
        function openModal() {
            document.getElementById('userModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>

</html>
