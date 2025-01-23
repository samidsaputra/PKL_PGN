<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

::after,
::before {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

a {
    text-decoration: none;
}

li {
    list-style: none;
}

h1 {
    font-weight: 600;
    font-size: 1.5rem;
}

body {
    font-family: 'Poppins', sans-serif;
}

.wrapper {
    display: flex;
}

.main {
    min-height: 100vh;
    width: 100%;
    overflow: hidden;
    transition: all 0.35s ease-in-out;
    background-color: #fafbfe;
}

#sidebar {
    width: 70px;
    min-width: 70px;
    z-index: 1000;
    transition: all .25s ease-in-out;
    background-color: #0e2238;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100%;
}

#sidebar.expand {
    width: 260px;
    min-width: 260px;
}

.toggle-btn {
    background-color: transparent;
    cursor: pointer;
    border: 0;
    padding: 1rem 1.5rem;
}

.toggle-btn i {
    font-size: 1.5rem;
    color: #FFF;
}

.sidebar-logo {
    margin: auto 0;
}

.sidebar-logo a {
    color: #FFF;
    font-size: 1.15rem;
    font-weight: 600;
}

#sidebar:not(.expand) .sidebar-logo,
#sidebar:not(.expand) a.sidebar-link span {
    display: none;
}

.sidebar-nav {
    padding: 2rem 0;
    flex: 1 1 auto;
}

a.sidebar-link {
    padding: .625rem 1.625rem;
    color: #FFF;
    display: block;
    font-size: 0.9rem;
    white-space: nowrap;
    border-left: 3px solid transparent;
}

.sidebar-link i {
    font-size: 1.1rem;
    margin-right: .75rem;
}

a.sidebar-link:hover {
    background-color: rgba(255, 255, 255, .075);
    border-left: 3px solid #3b7ddd;
}

.sidebar-item {
    position: relative;
}

#sidebar:not(.expand) .sidebar-item .sidebar-dropdown {
    position: absolute;
    top: 0;
    left: 70px;
    background-color: #0e2238;
    padding: 0;
    min-width: 15rem;
    display: none;
}

#sidebar:not(.expand) .sidebar-item:hover .has-dropdown+.sidebar-dropdown {
    display: block;
    max-height: 15em;
    width: 100%;
    opacity: 1;
}

#sidebar.expand .sidebar-link[data-bs-toggle="collapse"]::after {
    border: solid;
    border-width: 0 .075rem .075rem 0;
    content: "";
    display: inline-block;
    padding: 2px;
    position: absolute;
    right: 1.5rem;
    top: 1.4rem;
    transform: rotate(-135deg);
    transition: all .2s ease-out;
}

#sidebar.expand .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
    transform: rotate(45deg);
    transition: all .2s ease-out;
}
</style>
<aside id="sidebar">
  <div class="d-flex">
      <button class="toggle-btn" type="button">
        <i class="lni lni-dashboard-square-1"></i>
      </button>
      <div class="sidebar-logo">
          <a href="#">CodzSword</a>
      </div>
  </div>
  <ul class="sidebar-nav">
      <li class="sidebar-item">
          <a href="{{ route('dashboard')}}" class="sidebar-link">
            <i class="lni lni-home-2"></i>  
              <span>Dashboard</span>
          </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('daftar.pesanan')}}" class="sidebar-link">
              <i class="lni lni-agenda"></i>
              <span>Data Pesanan</span>
            </a>
      </li>
      <li class="sidebar-item">
          <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
              data-bs-target="#org" aria-expanded="false" aria-controls="org">
              <i class="lni lni-user-multiple-4"></i>
              <span>Organization</span>
          </a>
          <ul id="org" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
              <li class="sidebar-item">
                  <a href="{{ route('UserRegistration')}}" class="sidebar-link">User</a>
              </li>
              <li class="sidebar-item">
                  <a href="{{ route('daftar.satker')}}" class="sidebar-link">Satuan Kerja</a>
              </li>
          </ul>
      </li>
      <li class="sidebar-item">
          <a class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
              data-bs-target="#str" aria-expanded="false" aria-controls="str">
              <i class="lni lni-box-archive-1"></i>
              <span>Storage</span>
          </a>
          <ul id="str" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
              <li class="sidebar-item">
                  <a href="{{ route('daftar.barang')}}" class="sidebar-link">Data Sovenir</a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('kategori.index')}}" class="sidebar-link">Data Kategori</a>
            </li>
          </ul>
      </li>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link">
          <i class="lni lni-gear-1"></i>
          <span>Setting</span>
        </a>
      </li>
    </ul>
    <div class="sidebar-footer">
      <a href="#" class="sidebar-link">
        <i class="lni lni-bell-1"></i>
          <span>Notification</span>
      </a>
      <a href="/logout" class="sidebar-link">
          <i class="lni lni-exit"></i>
          <span>Logout</span>
      </a>
  </div>
</aside>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
crossorigin="anonymous"></script>
<script>
const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});
</script>
