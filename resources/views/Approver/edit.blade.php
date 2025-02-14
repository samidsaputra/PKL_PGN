<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link rel="stylesheet" href="{{ asset('css/Approver/edit.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-sidebar></x-sidebar>

    <a href="{{ url()->previous() }}" class="back-button">Back</a>

    <!-- Cart Icon -->
    <div class="cart-icon" onclick="toggleCart()">
        <i class="lni lni-cart-2"></i>
        <span class="cart-badge">0</span>
    </div>

    <!-- Cart Modal -->
    <div class="cart-modal">
        <div class="cart-header">
            <h2>Edit Order</h2>
            <button onclick="toggleCart()" style="border: none; background: none; cursor: pointer; font-size: 20px;">×</button>
        </div>
        <div class="cart-items">
            <!-- Cart items will be inserted here -->
        </div>
        <div class="cart-footer">
            <form id="editForm" class="edit-form" action="{{ route('orders.update', $order->noorder) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="acara">Nama Acara:</label>
                    <input type="text" name="acara" id="acara" value="{{ $order->acara }}" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_acara">Tanggal Acara:</label>
                    <input type="date" name="tanggal_acara" id="tanggal_acara" value="{{ $order->tanggal_acara }}" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_yang_diharapkan">Tanggal yang Diharapkan:</label>
                    <input type="date" name="tanggal_yang_diharapkan" id="tanggal_yang_diharapkan" value="{{ $order->tanggal_yang_diharapkan }}" required>
                </div>
                <div id="cart-items-container">
                    <!-- Items will be inserted here -->
                </div>
                <button type="submit" class="checkout-btn">Update Order</button>
            </form>
        </div>
    </div>

    <main>
        <div class="container">
            <h2>Edit Items for Order: {{ $order->noorder }}</h2>
            <div class="grid">
                @foreach($items as $item)
                <div class="product-card" onclick="addToCart('{{ $item->id }}', '{{ $item->Nama_Barang}}', '{{ $item->Kategori }}')">
                    <img src="https://via.placeholder.com/150" alt="{{ $item->Nama_Barang }}">
                    <div class="product-details">
                        <h3>{{ $item->Nama_Barang }}</h3>
                        <p>{{ $item->Kategori }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <script>
        let cart = [];

        // Initialize cart with existing order items
        cart = [
            @foreach($orderItems as $item)
                {
                    id: "{{ $item->id }}",
                    nama: "{{ $item->item }}",
                    jumlah: {{ $item->jumlah }}
                }@if(!$loop->last),@endif
            @endforeach
        ];
        

        function toggleCart() {
            document.querySelector('.cart-modal').classList.toggle('active');
        }

        function updateCartBadge() {
            const totalItems = cart.reduce((sum, item) => sum + item.jumlah, 0);
            document.querySelector('.cart-badge').textContent = totalItems;
        }

        function addToCart(itemId, itemName, itemCategory) {
            const existingItem = cart.find(item => item.id === itemId);
            
            if (existingItem) {
                existingItem.jumlah += 1;
            } else {
                cart.push({
                    id: itemId,
                    nama: itemName,
                    kategori: itemCategory,
                    jumlah: 1
                });
            }
            
            updateCartBadge();
            renderCart();
        }

        function updateQuantity(itemId, change) {
            const item = cart.find(item => item.id === itemId);
            if (item) {
                item.jumlah = Math.max(0, item.jumlah + change);
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
                        </div>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="updateQuantity('${item.id}', -1)">-</button>
                            <span>${item.jumlah}</span>
                            <button type="button" class="quantity-btn" onclick="updateQuantity('${item.id}', 1)">+</button>
                        </div>
                    </div>
                `;
            });

            const cartItemsContainer = document.getElementById('cart-items-container');
            cartItemsContainer.innerHTML = '';
            cart.forEach((item, index) => {
                cartItemsContainer.innerHTML += `
                    <input type="hidden" name="items[${index}][item]" value="${item.nama}">
                    <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">
                `;
            });
        }

        // Initial render
        renderCart();
        updateCartBadge();

        // Form submission
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }

            const formData = new FormData(this);
            const updateData = {
                acara: formData.get('acara'),
                tanggal_acara: formData.get('tanggal_acara'),
                tanggal_yang_diharapkan: formData.get('tanggal_yang_diharapkan'),
                items: cart,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(updateData)
                });

                const result = await response.json();

                if (response.ok) {
                    alert(result.message);
                    window.location.href = '/apr/approval'; // Redirect back to approval page
                } else {
                    throw new Error(result.message || 'Something went wrong');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
            }
        });
    </script>
</body>
</html>