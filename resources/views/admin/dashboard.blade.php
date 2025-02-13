<!-- resources/views/admin/dashboard.blade.php -->
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
    <x-SidebarAdmin></x-SidebarAdmin>
    
    <main class="dashboard">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Welcome To GasNlr</h1>
            <p class="dashboard-subtitle">We Serve the Sovenier</p>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <!-- Image Gallery Card -->
            <div class="dashboard-card large-card">
                <div class="image-gallery-container">
                    <div class="image-gallery">
                        <!-- Dummy items -->
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 1">
                            <div class="item-details">
                                <h3>Gantungan Kunci</h3>
                                <p>Rp 25.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 2">
                            <div class="item-details">
                                <h3>Kaos Custom</h3>
                                <p>Rp 85.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 3">
                            <div class="item-details">
                                <h3>Mug Custom</h3>
                                <p>Rp 35.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 4">
                            <div class="item-details">
                                <h3>Topi Custom</h3>
                                <p>Rp 45.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 5">
                            <div class="item-details">
                                <h3>Pin Badge</h3>
                                <p>Rp 15.000</p>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/200x150" alt="Souvenir 6">
                            <div class="item-details">
                                <h3>Sticker Pack</h3>
                                <p>Rp 10.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Grid -->
            <div class="dashboard-grid">
                <!-- Left Card -->
                <div class="dashboard-card">
                    <div class="card-content">
                        <span class="card-label">Total Order</span>
                    </div>
                </div>

                <!-- Right Card -->
                <div class="dashboard-card">
                    <div class="card-content">
                        <span class="card-label">Item's Stock</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>