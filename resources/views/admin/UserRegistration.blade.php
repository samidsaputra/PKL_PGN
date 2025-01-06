<!-- resources/views/auth/register.blade.php -->
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
    <div class="register-container">
        <div class="form-section">
            <h2>Create Account</h2>
        
            <form action ="" method="POST">
                @csrf
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Full Name" required>
                <input type="text" name="role" value="{{ old('role') }}" placeholder="Role" required>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
                <input type="password" name="password" placeholder="Enter Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <input type="submit" value="Sign Up">
            </form>
        </div>
    </div>
    <div class="table-user">

    </div>
</body>
</html>
