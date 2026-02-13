<?php
/**
 * Admin Theme Layout (Able Pro)
 * Path: app/Views/admin/theme/layout.php
 *
 * Kullanım örneği (bir sayfa view'ında):
 *   <?= $this->extend('admin/theme/layout') ?>
 *   <?= $this->section('content') ?>
 *     ... sayfa içeriği ...
 *   <?= $this->endSection() ?>
 */
?>
<!doctype html>
<html lang="en">
<head>
  <title><?= esc($title ?? 'Admin') ?></title>

  <!-- [Meta] -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <!-- [Favicon] icon -->
  <link rel="icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon" />

  <!-- [Font] Family -->
  <link rel="stylesheet" href="<?= base_url('assets/fonts/inter/inter.css') ?>" id="main-font-link" />

  <!-- [Icons] -->
  <link rel="stylesheet" href="<?= base_url('assets/fonts/phosphor/duotone/style.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/fonts/tabler-icons.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/fonts/feather.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/fonts/fontawesome.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/fonts/material.css') ?>" />

  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" id="main-style-link" />
  <link rel="stylesheet" href="<?= base_url('assets/css/style-preset.css') ?>" />

  <!-- (Opsiyonel) Header'a ekstra css eklemek istersen -->
  <?= $this->renderSection('styles') ?>
</head>

<body
  data-pc-preset="<?= esc($preset ?? 'preset-6') ?>"  <!-- preset-6: Orange, preset-5: Red/Bordo -->
  data-pc-sidebar-caption="true"
  data-pc-layout="vertical"
  data-pc-direction="ltr"
  data-pc-theme_contrast=""
  data-pc-theme="light"
>

  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <!-- Sidebar -->
  <?= view('admin/theme/sidebar', [
    'userName' => $userName ?? null,
    'userRole' => $userRole ?? null,
  ]) ?>

  <!-- Topbar -->
  <?= view('admin/theme/topbar', [
    'userName' => $userName ?? null,
  ]) ?>

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">

      <!-- Sayfa başlığı alanı istersen -->
      <?= $this->renderSection('page_header') ?>

      <!-- Sayfa içeriği -->
      <?= $this->renderSection('content') ?>
    </div>
  </div>
  <!-- [ Main Content ] end -->

  <!-- Footer -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col my-1">
          <p class="m-0">© <?= date('Y') ?> Admin Panel</p>
        </div>
        <div class="col-auto my-1">
          <ul class="list-inline footer-link mb-0">
            <li class="list-inline-item"><a href="<?= base_url('admin/dashboard') ?>">Home</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- ===== Required JS ===== -->
  <script src="<?= base_url('assets/js/plugins/popper.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/simplebar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/bootstrap.min.js') ?>"></script>

  <script src="<?= base_url('assets/js/plugins/i18next.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/i18nextHttpBackend.min.js') ?>"></script>

  <script src="<?= base_url('assets/js/icon/custom-font.js') ?>"></script>
  <script src="<?= base_url('assets/js/script.js') ?>"></script>
  <script src="<?= base_url('assets/js/theme.js') ?>"></script>
  <script src="<?= base_url('assets/js/multi-lang.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/feather.min.js') ?>"></script>

  <!-- (Opsiyonel) sayfaya özel js -->
  <?= $this->renderSection('scripts') ?>

  <!-- Varsayılan ayarlar -->
  <script>
    // İstersen burayı değiştirebiliriz: light/dark
    layout_change('light');

    // container genişliği
    change_box_container('false');

    // caption
    layout_caption_change('true');

    // rtl
    layout_rtl_change('false');

    // preset rengi (body data-pc-preset ile de çalışır; ikisi aynı olsun)
    preset_change('<?= esc($preset ?? 'preset-6') ?>');

    // layout
    main_layout_change('vertical');
  </script>

</body>
</html>