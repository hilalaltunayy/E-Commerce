<!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper">

    <!-- [Mobile Media Block] start -->
    <div class="me-auto pc-mob-drp">
      <ul class="list-unstyled">
        <li class="pc-h-item pc-sidebar-collapse">
          <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>

        <li class="pc-h-item pc-sidebar-popup">
          <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>

        <li class="pc-h-item d-none d-md-inline-flex">
          <form class="form-search">
            <i class="search-icon">
              <svg class="pc-icon">
                <use xlink:href="#custom-search-normal-1"></use>
              </svg>
            </i>
            <input type="search" class="form-control" placeholder="Ctrl + K" />
          </form>
        </li>
      </ul>
    </div>
    <!-- [Mobile Media Block end] -->

    <div class="ms-auto">
      <ul class="list-unstyled">

        <!-- theme switch -->
        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle arrow-none me-0"
             data-bs-toggle="dropdown" href="#" role="button"
             aria-haspopup="false" aria-expanded="false">
            <svg class="pc-icon">
              <use xlink:href="#custom-sun-1"></use>
            </svg>
          </a>
          <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
              <svg class="pc-icon"><use xlink:href="#custom-moon"></use></svg>
              <span>Dark</span>
            </a>
            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
              <svg class="pc-icon"><use xlink:href="#custom-sun-1"></use></svg>
              <span>Light</span>
            </a>
            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
              <svg class="pc-icon"><use xlink:href="#custom-setting-2"></use></svg>
              <span>Default</span>
            </a>
          </div>
        </li>

        <!-- language -->
        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle arrow-none me-0"
             data-bs-toggle="dropdown" href="#" role="button"
             aria-haspopup="false" aria-expanded="false">
            <svg class="pc-icon"><use xlink:href="#custom-language"></use></svg>
          </a>
          <div class="dropdown-menu dropdown-menu-end pc-h-dropdown lng-dropdown">
            <a href="#!" class="dropdown-item" data-lng="en"><span>English <small>(UK)</small></span></a>
            <a href="#!" class="dropdown-item" data-lng="fr"><span>français <small>(French)</small></span></a>
            <a href="#!" class="dropdown-item" data-lng="ro"><span>Română <small>(Romanian)</small></span></a>
            <a href="#!" class="dropdown-item" data-lng="cn"><span>中国人 <small>(Chinese)</small></span></a>
          </div>
        </li>

        <!-- quick settings dropdown (şimdilik statik) -->
        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle arrow-none me-0"
             data-bs-toggle="dropdown" href="#" role="button"
             aria-haspopup="false" aria-expanded="false">
            <svg class="pc-icon"><use xlink:href="#custom-setting-2"></use></svg>
          </a>
          <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
            <a href="#!" class="dropdown-item"><i class="ti ti-user"></i><span>My Account</span></a>
            <a href="#!" class="dropdown-item"><i class="ti ti-settings"></i><span>Settings</span></a>
            <a href="#!" class="dropdown-item"><i class="ti ti-headset"></i><span>Support</span></a>
            <a href="#!" class="dropdown-item"><i class="ti ti-lock"></i><span>Lock Screen</span></a>
            <a href="<?= base_url('logout') ?>" class="dropdown-item">
              <i class="ti ti-power"></i><span>Logout</span>
            </a>
          </div>
        </li>

        <!-- announcement -->
        <li class="pc-h-item">
          <a href="#" class="pc-head-link me-0" data-bs-toggle="offcanvas"
             data-bs-target="#announcement" aria-controls="announcement">
            <svg class="pc-icon"><use xlink:href="#custom-flash"></use></svg>
          </a>
        </li>

        <!-- notification (statik demo) -->
        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle arrow-none me-0"
             data-bs-toggle="dropdown" href="#" role="button"
             aria-haspopup="false" aria-expanded="false">
            <svg class="pc-icon"><use xlink:href="#custom-notification"></use></svg>
            <span class="badge bg-success pc-h-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
            <div class="dropdown-header d-flex align-items-center justify-content-between">
              <h5 class="m-0">Notifications</h5>
              <a href="#!" class="btn btn-link btn-sm">Mark all read</a>
            </div>

            <!-- İçerik demo bırakıldı -->
            <div class="dropdown-body text-wrap header-notification-scroll position-relative"
                 style="max-height: calc(100vh - 215px)">
              <p class="text-span">Today</p>
              <div class="card mb-2">
                <div class="card-body">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <svg class="pc-icon text-primary"><use xlink:href="#custom-layer"></use></svg>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <span class="float-end text-sm text-muted">2 min ago</span>
                      <h5 class="text-body mb-2">UI/UX Design</h5>
                      <p class="mb-0">Demo notification text.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center py-2">
              <a href="#!" class="link-danger">Clear all Notifications</a>
            </div>
          </div>
        </li>

        <!-- user profile -->
        <li class="dropdown pc-h-item header-user-profile">
          <a class="pc-head-link dropdown-toggle arrow-none me-0"
             data-bs-toggle="dropdown" href="#" role="button"
             aria-haspopup="false" data-bs-auto-close="outside"
             aria-expanded="false">
            <img src="<?= base_url('assets/images/user/avatar-2.jpg') ?>" alt="user-image" class="user-avtar" />
          </a>

          <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
            <div class="dropdown-header d-flex align-items-center justify-content-between">
              <h5 class="m-0">Profile</h5>
            </div>

            <div class="dropdown-body">
              <div class="profile-notification-scroll position-relative"
                   style="max-height: calc(100vh - 225px)">

                <div class="d-flex mb-1">
                  <div class="flex-shrink-0">
                    <img src="<?= base_url('assets/images/user/avatar-2.jpg') ?>" alt="user-image" class="user-avtar wid-35" />
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1"><?= esc($userName ?? 'Kullanıcı') ?> 🖖</h6>
                    <span><?= esc($userEmail ?? '') ?></span>
                    <div class="text-muted small"><?= esc($userRole ?? '') ?></div>
                  </div>
                </div>

                <hr class="border-secondary border-opacity-50" />

                <div class="d-grid mb-3">
                  <a class="btn btn-primary" href="<?= base_url('logout') ?>">
                    <svg class="pc-icon me-2"><use xlink:href="#custom-logout-1-outline"></use></svg>
                    Logout
                  </a>
                </div>

              </div>
            </div>
          </div>
        </li>

      </ul>
    </div>

  </div>
</header>

<!-- Announcement offcanvas -->
<div class="offcanvas pc-announcement-offcanvas offcanvas-end" tabindex="-1"
     id="announcement" aria-labelledby="announcementLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="announcementLabel">What's new announcement?</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    <p class="text-span">Today</p>
    <div class="card mb-3">
      <div class="card-body">
        <div class="align-items-center d-flex flex-wrap gap-2 mb-3">
          <div class="badge bg-light-success f-12">Big News</div>
          <p class="mb-0 text-muted">2 min ago</p>
          <span class="badge dot bg-warning"></span>
        </div>
        <h5 class="mb-3">Able Pro is Redesigned</h5>
        <p class="text-muted">Able Pro is completely renewed with high aesthetics UI.</p>
        <img src="<?= base_url('assets/images/layout/img-announcement-1.png') ?>" alt="img" class="img-fluid mb-3" />
      </div>
    </div>

    <p class="text-span mt-4">Yesterday</p>
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="mb-3">Featured Dashboard Template</h5>
        <p class="text-muted">Demo content.</p>
        <img src="<?= base_url('assets/images/layout/img-announcement-3.png') ?>" alt="img" class="img-fluid" />
      </div>
    </div>
  </div>
</div>
<!-- [ Header ] end -->