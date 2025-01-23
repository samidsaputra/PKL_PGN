<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>
<body >
    <x-SidebarAdmin></x-SidebarAdmin>
    <main class="dashboard-content">
        <div class="dashboard-header">Welcome to GasNir</div>
        <div class="card-holder">
            <div class="card">
                Item's
            </div>
            <div class="card">
                Total Order
            </div>
            <div class="card">
                Stock Item
            </div>
            <div class="card">
                ???
            </div>
        </div>
    </main>
</body>
</html>