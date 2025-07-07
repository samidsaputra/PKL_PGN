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

  <!-- Alert Container for error messages -->
  <div id="errorAlert" class="error-alert" style="display:none;"></div>

  <!-- Cart Icon -->
  <div class="cart-icon" onclick="toggleCart()">
    🛒
    <span class="cart-badge">0</span>
  </div>

  <!-- Confirmation Modal -->
  <div class="confirmation-modal" id="confirmationModal">
    <div class="confirmation-content">
      <h3>Add to Cart</h3>
      <p id="confirmationMessage">Select quantity for this item:</p>
      
      <div class="quantity-selector">
        <label>Quantity:</label>
        <div class="quantity-input-container">
          <button type="button" class="quantity-btn-modal" onclick="updateModalQuantity(-1)" id="decreaseBtn">−</button>
          <input type="number" class="quantity-input" id="modalQuantity" value="1" min="1">
          <button type="button" class="quantity-btn-modal" onclick="updateModalQuantity(1)" id="increaseBtn">+</button>
        </div>
      </div>
      
      <div class="confirmation-buttons">
        <button class="confirm-btn" onclick="confirmAddToCart()">Add to Cart</button>
        <button class="cancel-btn" onclick="closeConfirmation()">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Cart Modal -->
  <div class="cart-modal">
    <div class="cart-header">
      <h2>Shopping Cart</h2>
      <button onclick="toggleCart()" style="border: none; background: none; cursor: pointer; font-size: 20px;">×</button>
    </div>
    <div class="cart-items">
      <div class="empty-cart" id="emptyCart">
        <div style="font-size: 3rem; margin-bottom: 15px;">🛒</div>
        <p>Your cart is empty</p>
      </div>
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
        <button type="submit" class="checkout-btn">Place Order</button>
      </form>
    </div>
  </div>

  <main>
    <div class="container">
      <h2>Choose Your Items</h2>
      <div class="grid">
        @foreach($items as $item)
        <div class="product-card" onclick="showConfirmation('{{ $item->id }}', '{{ $item->Nama_Barang }}', '{{ $item->Kategori }}', {{ $item->Stok }})">
          <div class="product-image-container">
            <img src="{{ url('storage/'.$item->Gambar) }}" alt="{{ $item->Nama_Barang }}">
          </div>
          <div class="product-details">
            <h3>{{ $item->Nama_Barang }}</h3>
            <p class="product-category">{{ $item->Kategori }}</p>
            <span class="product-stock {{ $item->Stok <= 5 ? ($item->Stok == 0 ? 'out-of-stock' : 'low-stock') : '' }}">
              @if($item->Stok == 0)
                Out of Stock
              @elseif($item->Stok <= 5)
                Low Stock: {{ $item->Stok }}
              @else
                Stock: {{ $item->Stok }}
              @endif
            </span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </main>

  <script>
let cart = [];
let pendingItem = null;
let modalQuantity = 1;

function toggleCart() {
  document.querySelector('.cart-modal').classList.toggle('active');
}

function updateCartBadge() {
  const totalItems = cart.reduce((sum, item) => sum + item.jumlah, 0);
  document.querySelector('.cart-badge').textContent = totalItems;

  const emptyCart = document.getElementById('emptyCart');
  if (emptyCart) {
    emptyCart.style.display = cart.length === 0 ? 'block' : 'none';
  }
}

function updateModalQuantity(change) {
  if (!pendingItem) return;

  const newQuantity = modalQuantity + change;
  const existingItem = cart.find(item => item.id === pendingItem.id);
  const currentCartQuantity = existingItem ? existingItem.jumlah : 0;
  const maxAvailable = pendingItem.stock - currentCartQuantity;

  if (newQuantity >= 1 && newQuantity <= maxAvailable) {
    modalQuantity = newQuantity;
    document.getElementById('modalQuantity').value = modalQuantity;
  }

  document.getElementById('decreaseBtn').disabled = modalQuantity <= 1;
  document.getElementById('increaseBtn').disabled = modalQuantity >= maxAvailable;
}

document.getElementById('modalQuantity').addEventListener('input', function () {
  if (!pendingItem) return;

  let inputVal = parseInt(this.value);
  const existingItem = cart.find(item => item.id === pendingItem.id);
  const currentCartQuantity = existingItem ? existingItem.jumlah : 0;
  const maxAvailable = pendingItem.stock - currentCartQuantity;

  // Validasi input
  if (isNaN(inputVal) || inputVal < 1) {
    inputVal = 1;
  } else if (inputVal > maxAvailable) {
    inputVal = maxAvailable;
  }

  modalQuantity = inputVal;
  this.value = modalQuantity;

  // Update tombol enable/disable
  document.getElementById('decreaseBtn').disabled = modalQuantity <= 1;
  document.getElementById('increaseBtn').disabled = modalQuantity >= maxAvailable;
});


function showConfirmation(itemId, itemName, itemCategory, stock) {
  if (stock === 0) {
    showErrorMessage('Sorry, this item is out of stock!');
    return;
  }

  pendingItem = {
    id: itemId,
    nama: itemName,
    kategori: itemCategory,
    stock: stock
  };

  const existingItem = cart.find(item => item.id === itemId);
  const currentCartQuantity = existingItem ? existingItem.jumlah : 0;
  const maxAvailable = stock - currentCartQuantity;

  if (maxAvailable <= 0) {
    showErrorMessage(`Sorry, you already have the maximum available quantity (${stock}) for this item in your cart!`);
    return;
  }

  modalQuantity = 1;
  document.getElementById('modalQuantity').value = modalQuantity;

  let message = `"${itemName}"`;
  if (existingItem) {
    message += ` (Currently in cart: ${existingItem.jumlah})`;
  }
  message += `\nAvailable to add: ${maxAvailable}`;

  document.getElementById('confirmationMessage').textContent = message;

  document.getElementById('decreaseBtn').disabled = modalQuantity <= 1;
  document.getElementById('increaseBtn').disabled = modalQuantity >= maxAvailable;

  document.getElementById('confirmationModal').classList.add('active');
}

function confirmAddToCart() {
  if (!pendingItem) return;

  const existingItem = cart.find(item => item.id === pendingItem.id);

  if (existingItem) {
    const totalQuantity = existingItem.jumlah + modalQuantity;
  if (totalQuantity > pendingItem.stock) {
    showErrorMessage(`Sorry, only ${pendingItem.stock} items available in stock!`);
    closeConfirmation();
    return;
  }
    existingItem.jumlah += modalQuantity;
  } else {
    cart.push({
      id: pendingItem.id,
      nama: pendingItem.nama,
      kategori: pendingItem.kategori,
      jumlah: modalQuantity,
      maxStock: pendingItem.stock
    });
  }

  updateCartBadge();
  renderCart();
  closeConfirmation();

  const quantityText = modalQuantity > 1 ? ` (${modalQuantity} items)` : '';
  showSuccessMessage(`"${pendingItem.nama}"${quantityText} added to cart!`);
}

function closeConfirmation() {
  document.getElementById('confirmationModal').classList.remove('active');
  pendingItem = null;
  modalQuantity = 1;
}

function showSuccessMessage(message) {
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 100px;
    right: 30px;
    background: var(--success-color);
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    z-index: 3000;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
  `;
  notification.textContent = message;
  document.body.appendChild(notification);

  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease forwards';
    setTimeout(() => notification.remove(), 300);
  }, 2000);
}

function updateQuantity(itemId, change) {
  const itemIndex = cart.findIndex(item => item.id === itemId);
  if (itemIndex === -1) return;

  const item = cart[itemIndex];
  const newQuantity = item.jumlah + change;

  if (newQuantity > item.maxStock) {
    alert(`Sorry, only ${item.maxStock} items available in stock!`);
    return;
  }

  if (newQuantity <= 0) {
    cart.splice(itemIndex, 1);
  } else {
    item.jumlah = newQuantity;
  }

  updateCartBadge();
  renderCart();
}

function renderCart() {
  const cartContainer = document.querySelector('.cart-items');

  if (cart.length === 0) {
    cartContainer.innerHTML = `
      <div class="empty-cart" id="emptyCart">
        <div style="font-size: 3rem; margin-bottom: 15px;">🛒</div>
        <p>Your cart is empty</p>
      </div>
    `;
  } else {
    cartContainer.innerHTML = '';
    cart.forEach(item => {
      cartContainer.innerHTML += `
        <div class="cart-item">
          <div class="cart-item-details">
            <h3>${item.nama}</h3>
            <p>${item.kategori}</p>
          </div>
          <div class="quantity-controls">
            <button class="quantity-btn" onclick="updateQuantity('${item.id}', -1)">−</button>
            <span>${item.jumlah}</span>
            <button class="quantity-btn" onclick="updateQuantity('${item.id}', 1)">+</button>
          </div>
        </div>
      `;
    });
  }

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

const orderConfirmationModal = document.createElement('div');
orderConfirmationModal.id = 'orderConfirmationModal';
orderConfirmationModal.className = 'confirmation-modal';
orderConfirmationModal.innerHTML = `
  <div class="confirmation-content" role="dialog" aria-modal="true" aria-labelledby="orderConfirmationTitle" aria-describedby="orderConfirmationDesc">
    <h3 id="orderConfirmationTitle">Confirm Order</h3>
    <p id="orderConfirmationDesc">Are you sure you want to place this order?</p>
    <div class="confirmation-buttons">
      <button class="confirm-btn" id="confirmOrderBtn" type="button">Yes, Place Order</button>
      <button class="cancel-btn" id="cancelOrderBtn" type="button">Cancel</button>
    </div>
  </div>
`;
document.body.appendChild(orderConfirmationModal);

const checkoutForm = document.getElementById('checkoutForm');
checkoutForm.addEventListener('submit', function (e) {
  e.preventDefault();

  if (cart.length === 0) {
    showErrorMessage('Cart is empty!');
    return;
  }

  for (const item of cart) {
    if (item.jumlah > item.maxStock) {
      showErrorMessage(`Sorry, the quantity for ${item.nama} exceeds available stock!`);
      return;
    }
  }

  // Show the custom confirmation modal
  orderConfirmationModal.classList.add('active');
  // Set focus to confirm button for accessibility
  document.getElementById('confirmOrderBtn').focus();
});

document.getElementById('confirmOrderBtn').addEventListener('click', async function () {
  orderConfirmationModal.classList.remove('active');

  const formData = new FormData(checkoutForm);
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
        checkoutForm.reset();
        location.reload()
      } else {
        alert(result.message || 'Something went wrong, please try again.');
      }
    } else {
      throw new Error(result.message || `HTTP error! Status: ${response.status}`);
    }
  } catch (error) {
    console.error('Error:', error);
    showErrorMessage(`${error.message}`);
  }
});

document.getElementById('cancelOrderBtn').addEventListener('click', function () {
  orderConfirmationModal.classList.remove('active');
  // Return focus to the Place Order button for accessibility
  document.querySelector('.checkout-btn').focus();
});

document.getElementById('confirmationModal').addEventListener('click', function (e) {
  if (e.target === this) {
    closeConfirmation();
  }
});

updateCartBadge();


function showErrorMessage(message) {
  const alertBox = document.getElementById('errorAlert');
  alertBox.textContent = message;
  alertBox.style.display = 'block';
  alertBox.style.opacity = '1';

  setTimeout(() => {
    alertBox.style.transition = 'opacity 0.5s ease';
    alertBox.style.opacity = '0';
    setTimeout(() => {
      alertBox.style.display = 'none';
      alertBox.style.transition = '';
    }, 500);
  }, 3500);
}

  </script>
</body>

</html>