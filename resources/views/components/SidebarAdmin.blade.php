<style>
/* Side Navbar */
.side-navbar {
  width: 250px;
  background-color: #32599c;
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 20px;
  box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
}

.side-navbar .logo img {
  width: 40px;
  height: 40px;
  margin-bottom: 20px;
}

.side-navbar .menu {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.side-navbar .menu-item {
  color: #d1d5db;
  text-decoration: none;
  padding: 10px 15px;
  border-radius: 4px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.side-navbar .menu-item:hover,
.side-navbar .menu-item.active {
  background-color: #85C226;
  color: white;
}

.side-navbar .actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
}

.logo img {
    width: 40px;
    height: 40px;
}

.menu {
    display: flex;
    gap: 15px;
}

.menu-item {
    color: #d1d5db;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.menu-item:hover,
.menu-item.active {
    background-color: #85C226;
    color: white;
}

.actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-btn {
    background: none;
    border: none;
    color: #d1d5db;
    cursor: pointer;
    padding: 8px;
    border-radius: 3%;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.notification-btn:hover {
  background-color: #85C226;
  color: white;
}

.icon {
    width: 24px;
    height: 24px;
    fill: currentColor;
}

.profile {
  display: flex;
  align-items: center;
  gap: 10px;
}

.profile-btn {
  display: flex;
  align-items: center;
  background: none;
  border: none;
  cursor: pointer;
  gap: 10px;
}

.profile-btn img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
}

.profile-btn span {
  color: #d1d5db;
  font-size: 16px;
  font-weight: bold;
  transition: color 0.3s ease;
}

.profile-btn:hover span {
  color: white;
}
</style>
<div class="side-navbar">
    <div class="logo">
        <img src="https://via.placeholder.com/40" alt="Logo">
    </div>
    <div class="menu">
        <a href="{{ route('dashboard') }}" class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('UserRegistration') }}" class="menu-item {{ Request::is('UserReg') ? 'active' : '' }}">User</a>
        <a href="#" class="menu-item {{ Request::is('satuan-kerja') ? 'active' : '' }}">Satuan Kerja</a>
        <a href="#" class="menu-item {{ Request::is('barang') ? 'active' : '' }}">Barang</a>
        <a href="#" class="menu-item {{ Request::is('stok') ? 'active' : '' }}">Stok</a>
    </div>
    <div class="actions">
        <button class="notification-btn">
            <span class="sr-only">View notifications</span>
            <svg viewBox="0 0 24 24" class="icon">
                <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"></path>
            </svg>
        </button>
        <div class="profile">
            <button class="profile-btn">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User">
                <span>Profile</span>
            </button>
        </div>
    </div>
</div>