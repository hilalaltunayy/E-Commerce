<style>
    .pc-sidebar { background: #d57405 !important; } /* Sidebar Bordo */
    .pc-sidebar .pc-link, .pc-sidebar .pc-mtext, .pc-sidebar .pc-micon { color: #0b1c29 !important; } /* Yazılar Ekru */
    .pc-sidebar .pc-link:hover { background: rgba(230, 126, 34, 0.2) !important; color: #E67E22 !important; }
    .pc-user-card { background: rgba(255, 255, 255, 0.1) !important; border: none; }

    .sub-bullet {
        font-weight: bold;
        margin-right: 8px;
        color: #c89e79;
    }
</style>

<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header text-center py-3">
        <a href="<?= base_url('dashboard_anasayfa') ?>" class="b-brand">
            <h4 class="text-white fw-bold"><i class="ti ti-book-open me-2"></i>Kitap Dünyası</h4>
        </a>
    </div>
    
    <div class="navbar-content">
      <div class="card pc-user-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <img src="<?= base_url('assets/images/user/avatar-1.jpg') ?>" class="user-avtar wid-45 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0 text-white"><?= session()->get('userData')['name'] ?? 'Misafir Kullanıcı' ?></h6>
              <small class="text-white-50">Mühendis / Yönetici</small>
            </div>
          </div>
        </div>
      </div>

      <li class="pc-item pc-caption"><label>Ürün Yönetimi</label></li>
        
        <li class="pc-item">
          <a href="<?= base_url('products/selection') ?>" class="pc-link">
            <span class="pc-micon"><b class="sub-bullet"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="#ffffff"><path d="M480-160q-48-38-104-59t-116-21q-42 0-82.5 11T100-198q-21 11-40.5-1T40-234v-482q0-11 5.5-21T62-752q46-24 96-36t102-12q58 0 113.5 15T480-740v484q51-32 107-48t113-16q36 0 70.5 6t69.5 18v-480q15 5 29.5 10.5T898-752q11 5 16.5 15t5.5 21v482q0 23-19.5 35t-40.5 1q-37-20-77.5-31T700-240q-60 0-116 21t-104 59Zm80-200v-380l200-200v400L560-360Zm-160 65v-396q-33-14-68.5-21.5T260-720q-37 0-72 7t-68 21v397q35-13 69.5-19t70.5-6q36 0 70.5 6t69.5 19Zm0 0v-396 396Z"/></svg></b></span>
            <span class="pc-mtext" style="color:#ffffff;">Ürünler</span>
          </a>
        </li>
        
      <li class="pc-item pc-caption"><label>Stok Yönetimi</label></li>
        <li class="pc-item">
          <a href="<?= base_url('products/stock-management') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-package"></i></span>
            <span class="pc-mtext">Stok Takip Paneli</span>
          </a>
        </li>

        <li class="pc-item pc-caption"><label>Sipariş Yönetimi</label></li>
         <li class="pc-item">
          <a href="<?= base_url('orders') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
            <span class="pc-mtext">Siparişler</span>
          </a>
        </li>

        <li class="pc-item pc-caption"><label>Sistem</label></li>
        <li class="pc-item">
          <a href="<?= base_url('logout') ?>" class="pc-link text-danger">
            <span class="pc-micon"><i class="ti ti-power"></i></span>
            <span class="pc-mtext">Çıkış Yap</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>