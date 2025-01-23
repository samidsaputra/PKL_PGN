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
      <button onclick="toggleCart()" style="border: none; background: none; cursor: pointer; font-size: 20px;"></button>
    </div>
    <div class="cart-items">
      <!-- Cart items will be inserted here -->
    </div>
    <div class="cart-footer">
      <form id="checkoutForm" class="checkout-form" action="{{ route('cart.checkout') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="acara">Nama Acara:</label>
          <input type="text" name="acara" value="{{ old('acara')}}" placeholder="Masukkan Nama Acara" required>
        </div>
        <div class="form-group">
          <label for="tanggal_acara">Tanggal Acara:</label>
          <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara')}}" required>
        </div>
        <div class="form-group">
          <label for="tanggal_yang_diharapkan">Tanggal yang Diharapkan:</label>
          <input type="date" name="tanggal_yang_diharapkan" value="{{ old('tanggal_yang_diharapkan')}}" required>
        </div>
        <div id="cart-items-container">
          <!-- Items dari cart akan dimasukkan di sini -->
        </div>
        <button type="submit" class="checkout-btn" value="Order">Order</button>
      </form>
    </div>
  </div>

  <main>
    <div class="container">
      <h2>Item</h2>
      <div class="grid">
        @foreach($items as $item)
        <div class="product-card" onclick="addToCart('{{ $item->id }}', '{{ $item->Nama_Barang }}', '{{ $item->Kategori }}')">
          <img src="https://via.placeholder.com/150" alt="{{ $item->Nama_Barang }}">
          <div class="product-details">
            <h3>{{ $item->Nama_Barang }}</h3>
            <p>{{ $item->Kategori }}</p>
            <p>{{ $item->Deskripsi }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </main>

  <script>
   $(document).ready(function() {
  let cart = [];

  window.toggleCart = function() {
    $('.cart-modal').toggleClass('active');
  }

  window.addToCart = function(itemId, itemName, itemCategory) {
    // Convert itemId to integer
    const id = parseInt(itemId);
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
      existingItem.jumlah += 1;
    } else {
      cart.push({
        id: id, // Now storing as integer
        nama: itemName,
        kategori: itemCategory,
        jumlah: 1
      });
    }
    updateCartBadge();
    renderCart();
  }

  window.updateQuantity = function(itemId, change) {
    // Convert itemId to integer
    const id = parseInt(itemId);
    const item = cart.find(item => item.id === id);
    if (item) {
      item.jumlah = Math.max(0, item.jumlah + change);
      if (item.jumlah === 0) {
        cart = cart.filter(i => i.id !== id);
      }
      updateCartBadge();
      renderCart();
    }
  }

  function updateCartBadge() {
    const totalItems = cart.reduce((sum, item) => sum + item.jumlah, 0);
    $('.cart-badge').text(totalItems);
  }

  function renderCart() {
    $('.cart-items').empty();
    cart.forEach(item => {
      $('.cart-items').append(`
        <div class="cart-item">
          <div class="cart-item-details">
            <h3>${item.nama}</h3>
            <p>${item.kategori}</p>
          </div>
          <div class="quantity-controls">
            <button type="button" class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
            <span>${item.jumlah}</span>
            <button type="button" class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
          </div>
        </div>
      `);
    });
  }

  $('#checkoutForm').submit(function(e) {
    e.preventDefault();

    if (cart.length === 0) {
      alert('Cart is empty!');
      return;
    }

    // Ensure all IDs are integers before sending
    const cartWithIntIds = cart.map(item => ({
      ...item,
      id: parseInt(item.id),
      jumlah: parseInt(item.jumlah)
    }));

    let checkoutData = {
      acara: $('input[name="acara"]').val(),
      tanggal_acara: $('input[name="tanggal_acara"]').val(),
      tanggal_yang_diharapkan: $('input[name="tanggal_yang_diharapkan"]').val(),
      items: cartWithIntIds
    };

    $.ajax({
      url: '{{ route("cart.checkout") }}',
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      data: JSON.stringify(checkoutData),
      dataType: 'json',
      processData: false,
      success: function(response) {
        if (response.status === 'success') {
          alert(response.message);
          cart = [];
          updateCartBadge();
          renderCart();
          toggleCart();
          $('#checkoutForm')[0].reset();
        } else {
          alert(response.message || 'Something went wrong, please try again.');
        }
      },
      error: function(xhr) {
        console.error('Error:', xhr);
        const errorMessage = xhr.responseJSON?.message || `Error! Status: ${xhr.status}`;
        console.error('Validation Error:', errorMessage);
        alert(errorMessage);
      }
    });
  });
});
  </script>
  
</body>
</html>
