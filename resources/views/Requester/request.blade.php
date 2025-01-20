<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Item</title>
  <style>
    body {
      background-color: white;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 16px;
    }
    .heading {
      font-size: 1.5rem;
      font-weight: bold;
      color: #1a202c;
      margin-bottom: 24px;
    }
    main {
    padding: 2rem;
    margin-left: 250px; /* Adjust based on your sidebar width */
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 24px;
    }
    .product-card {
      position: relative;
      background-color: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      height: 260px;
      width: 150px;
      padding: 15px;
    }
    .product-card img {
      width: 100%;
      height: auto;
      object-fit: cover;
      transition: opacity 0.3s ease;
    }
    .product-card:hover img {
      opacity: 0.75;
    }
    .product-details {
      padding: 16px;
      display: flex;
      justify-content: space-between;
    }
    .product-title {
      font-size: 0.875rem;
      color: #4a5568;
      text-decoration: none;
    }
    .product-title:hover {
      text-decoration: underline;
    }
    .product-color {
      font-size: 0.875rem;
      color: #a0aec0;
      margin-top: 4px;
    }
    .product-price {
      font-size: 0.875rem;
      font-weight: 500;
      color: #1a202c;
    }
  </style>
</head>
<body>
    <x-sidebar></x-sidebar>
    <main>

        <div class="container">
            <h2 class="heading">Item</h2>
            
            <div class="grid">
                <div class="product-card">
                    <img src="https://tailwindui.com/plus/img/ecommerce-images/product-page-01-related-product-01.jpg" alt="Front of men's Basic Tee in black.">
                    <div class="product-details">
                        <div>
                            <a href="#" class="product-title">Basic Tee</a>
                            <p class="product-color">Black</p>
                        </div>
                        <p class="product-price">$35</p>
                    </div>
                </div>
                
                <!-- Tambahkan produk lainnya di sini -->
                
            </div>
        </div>
    </main>
</body>
</html>
