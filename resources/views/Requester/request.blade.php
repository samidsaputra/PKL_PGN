<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-commerce Flow</title>
  <link rel="stylesheet" href="{{ asset('css/Requester/request.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <x-sidebar></x-sidebar>

  <!-- Cart Icon -->
  <div class="cart-icon" onclick="toggleCart()">
    <i class="lni lni-cart-2"></i>
    <span class="cart-badge">0</span>
  </div>

  <!-- Cart Modal -->
  <div class="cart-modal">
    <div class="cart-header">
      <h2>Cart</h2>
      <button onclick="toggleCart()" style="border: none; background: none; cursor: pointer; font-size: 20px;">×</button>
    </div>
    <div class="cart-items">
      <!-- Cart items will be inserted here -->
    </div>
    <div class="cart-footer">
      <form id="checkoutForm" class="checkout-form" action="{{ route('cart.checkout') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="acara">Nama Acara:</label>
          <input type="text" name="acara" id="acara" placeholder="Masukkan Nama Acara" required>
        </div>
        <div class="form-group">
          <label for="tanggal_acara">Tanggal Acara:</label>
          <input type="date" name="tanggal_acara" id="tanggal_acara" required>
        </div>
        <div class="form-group">
          <label for="tanggal_yang_diharapkan">Tanggal yang Diharapkan:</label>
          <input type="date" name="tanggal_yang_diharapkan" id="tanggal_yang_diharapkan" required>
        </div>
        <div id="cart-items-container">
          <!-- Items dari cart akan dimasukkan di sini -->
        </div>
        <button type="submit" class="checkout-btn">Order</button>
      </form>
    </div>
  </div>

  <main>
    <div class="container">
      <h2>Item</h2>
      <div class="grid">
        @foreach($items as $item)
        <div class="product-card" onclick="addToCart('{{ $item->id }}', '{{ $item->Nama_Barang }}', '{{ $item->Kategori }}')">
          <img src="{{ url('storage/'.$item->Gambar) }}">
          <div class="product-details">
            <h3>{{ $item->Nama_Barang }}</h3>
            <p>{{ $item->Kategori }}</p>
            <p>Stock: {{ $item->Stok }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </main>

  <script>
    let cart = [];

    function toggleCart() {
      document.querySelector('.cart-modal').classList.toggle('active');
    }

    function updateCartBadge() {
      const totalItems = cart.reduce((sum, item) => sum + item.jumlah, 0);
      document.querySelector('.cart-badge').textContent = totalItems;
    }

    function addToCart(itemId, itemName, itemCategory, stock) {
      const existingItem = cart.find(item => item.id === itemId);

      if (existingItem) {
        // Check if adding one more would exceed stock
        if (existingItem.jumlah + 1 > stock) {
          alert(`Sorry, only ${stock} items available in stock!`);
          return;
        }
        existingItem.jumlah += 1;
      } else {
        if (stock < 1) {
          alert('Sorry, this item is out of stock!');
          return;
        }
        cart.push({
          id: itemId,
          nama: itemName,
          kategori: itemCategory,
          jumlah: 1,
          maxStock: stock // Store max stock for validation
        });
      }

      updateCartBadge();
      renderCart();
    }

    function updateQuantity(itemId, change) {
      const item = cart.find(item => item.id === itemId);
      if (item) {
        const newQuantity = item.jumlah + change;

        // Check if new quantity would exceed stock
        if (newQuantity > item.maxStock) {
          alert(`Sorry, only ${item.maxStock} items available in stock!`);
          return;
        }

        item.jumlah = Math.max(0, newQuantity);
        if (item.jumlah === 0) {
          cart = cart.filter(i => i.id !== itemId);
        }
        updateCartBadge();
        renderCart();
      }
    }

    function renderCart() {
      const cartContainer = document.querySelector('.cart-items');
      cartContainer.innerHTML = '';
      cart.forEach(item => {
        cartContainer.innerHTML += `
          <div class="cart-item">
            <div class="cart-item-details">
              <h3>${item.nama}</h3>
              <p>${item.kategori}</p>
            </div>
            <div class="quantity-controls">
              <button class="quantity-btn" onclick="updateQuantity('${item.id}', -1)">-</button>
              <span>${item.jumlah}</span>
              <button class="quantity-btn" onclick="updateQuantity('${item.id}', 1)">+</button>
            </div>
          </div>
        `;
      });
      // Add cart items to checkout form
      const cartItemsContainer = document.getElementById('cart-items-container');
      cartItemsContainer.innerHTML = '';
      cart.forEach((item, index) => {
        cartItemsContainer.innerHTML += `
          <input type="hidden" name="items[${index}][item]" value="${item.nama}">
          <input type="hidden" name="items[${index}][kategori]" value="${item.kategori}">
          <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">
        `;
      });
    }
    document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      if (cart.length === 0) {
        alert('Cart is empty!');
        return;
      }

      // Validate stock one more time before submission
      for (const item of cart) {
        if (item.jumlah > item.maxStock) {
          alert(`Sorry, the quantity for ${item.nama} exceeds available stock!`);
          return;
        }
      }

      const formData = new FormData(this);
      const checkoutData = {
        acara: formData.get('acara'),
        tanggal_acara: formData.get('tanggal_acara'),
        tanggal_yang_diharapkan: formData.get('tanggal_yang_diharapkan'),
        items: cart.map(item => ({
          id: item.id,
          nama: item.nama,
          jumlah: item.jumlah
        }))
      };

      try {
        const response = await fetch('{{ route("cart.checkout") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(checkoutData)
        });

        const result = await response.json();

        if (response.ok) {
          if (result.status === 'success') {
            alert(result.message);
            cart = [];
            updateCartBadge();
            renderCart();
            toggleCart();
            this.reset();
          } else {
            alert(result.message || 'Something went wrong, please try again.');
          }
        } else {
          throw new Error(result.message || `HTTP error! Status: ${response.status}`);
        }
      } catch (error) {
        console.error('Error:', error);
        alert(`${error.message}`);
      }
    });
  </script>
</body>

</html>