<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin Panel') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f6f6f6; }
        .topbar { background:#111827; color:#fff; padding:14px 18px; }
        .wrap { display:flex; min-height: calc(100vh - 48px); }
        .sidebar { width:220px; background:#ffffff; border-right:1px solid #e5e7eb; padding:14px; }
        .content { flex:1; padding:18px; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px; }
        a { color:#111827; text-decoration:none; display:block; padding:8px 10px; border-radius:8px; }
        a:hover { background:#f3f4f6; }
        .muted { color:#6b7280; font-size:14px; }
    </style>
</head>
<body>

<div class="topbar">
    <strong>Admin Panel</strong>
    <span class="muted">— <?= esc($userEmail ?? '') ?> (<?= esc($userRole ?? '') ?>)</span>
</div>

<div class="wrap">
    <div class="sidebar">
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/orders">Orders</a>
        <a href="/admin/products">Products</a>
        <hr>
        <a href="/logout">Logout</a>
    </div>

    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>
</div>

</body>
</html>