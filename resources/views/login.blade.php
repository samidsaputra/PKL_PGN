<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i>
            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="form-section">
            <h2>Hello, Welcome Back</h2>
        
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required autofocus>
                <input type="password" name="password" placeholder="Enter Password" required>
                <input type="submit" value="Sign In">

                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>                
            </form>
        </div>
    </div>
</body>
</html>