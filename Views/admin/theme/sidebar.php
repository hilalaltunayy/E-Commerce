<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">

    <!-- Logo -->
    <div class="m-header">
      <a href="<?= base_url('admin/dashboard') ?>" class="b-brand text-primary">
        <img src="<?= base_url('assets/images/logo-dark.svg') ?>" class="img-fluid logo-lg" alt="logo" />
        <span class="badge bg-light-success rounded-pill ms-2 theme-version">Admin</span>
      </a>
    </div>

    <div class="navbar-content">

      <!-- User card -->
      <div class="card pc-user-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <img src="<?= base_url('assets/images/user/avatar-1.jpg') ?>" alt="user-image" class="user-avtar wid-45 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3 me-2">
              <h6 class="mb-0"><?= esc($userName ?? 'Kullanıcı') ?></h6>
              <small><?= esc($userRole ?? '') ?></small>
            </div>
          </div>

          <div class="pt-3">
            <a href="<?= base_url('logout') ?>">
              <i class="ti ti-power"></i>
              <span>Logout</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Menu -->
      <ul class="pc-navbar">

        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>

        <li class="pc-item">
          <a href="<?= base_url('admin/dashboard') ?>" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon"><use xlink:href="#custom-status-up"></use></svg>
            </span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="<?= base_url('admin/orders') ?>" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon"><use xlink:href="#custom-document"></use></svg>
            </span>
            <span class="pc-mtext">Orders</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="<?= base_url('admin/products') ?>" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon"><use xlink:href="#custom-shopping-bag"></use></svg>
            </span>
            <span class="pc-mtext">Products</span>
          </a>
        </li>

      </ul>

    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->