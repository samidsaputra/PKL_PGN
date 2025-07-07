<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>

<body>
    <x-sidebar></x-sidebar>
    <main class="main-content">
        <div class="dashboard-header">Welcome to Gasnir</div>
        <div class="container">
            <div class="stats-grid">
                <a class="stats-section" href="{{ route('approver.approved')}}">
                    <div class="stat-item">
                        <div class="stat-title">Total Orders:</div>
                        <div class="stat-value">{{ $totalorders->count() }}</div>
                    </div>
                </a>
                <a class="stats-section" href="{{ route('approver.approved')}}">
                    <div class="stat-item">
                        <div class="stat-title">Completed Orders:</div>
                        <div class="stat-value">{{ $approvedorders->count() }}</div>
                    </div>
                </a>
                <a class="stats-section" href="{{ route('approver.approval')}}">
                    <div class="stat-item">
                        <div class="stat-title">Pending Orders:</div>
                        <div class="stat-value">{{ $pendingorders->count() }}</div>
                    </div>
                </a>
                <a class="stats-section" href="{{ route('approver.approval')}}">
                    <div class="stat-item">
                        <div class="stat-title">Done Revision Orders:</div>
                        <div class="stat-value">{{ $revisionorders->count() }}</div>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>

</html>