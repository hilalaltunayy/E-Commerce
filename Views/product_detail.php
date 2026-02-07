<?= $this->extend("layouts/main") ?>

<?= $this->section("content") ?>
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard_anasayfa') ?>">Anasayfa</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('products') ?>">Ürün Listesi</a></li>
                            <li class="breadcrumb-item" aria-current="page"><?= esc($product['product_name']) ?> Detayları</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card overflow-hidden">
                    <div class="card-body bg-light-primary">
                        <div class="text-center">
                            <div class="avtar avtar-xl bg-primary text-white mx-auto">
                                <i class="ti ti-box f-30"></i>
                            </div>
                            <h4 class="mt-3 mb-0"><?= esc($product['product_name']) ?></h4>
                            <p class="text-muted">Ürün Kimliği: #<?= $product['id'] ?></p>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="ti ti-currency-dollar me-2"></i>Fiyat:</span>
                                <span class="fw-bold text-dark"><?= number_format($product['price'], 2) ?> TL</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="ti ti-package me-2"></i>Mevcut Stok:</span>
                                <span class="badge bg-success rounded-pill"><?= $product['stock'] ?> Adet</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="ti ti-calendar me-2"></i>Kayıt Tarihi:</span>
                                <span class="text-muted"><?= !empty($product['created_at']) ? date('d.m.Y H:i', strtotime($product['created_at'])) : '-' ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Aylık Stok Hareket Analizi</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary">2026 Yılı</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="stock-movement-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("scripts") ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var options = {
        series: [{
            name: 'Stok Girişi (Adet)',
            data: [31, 40, 28, 51, 42, 109, 100, 80, 95, 120, 110, 150]
        }, {
            name: 'Stok Çıkışı (Satış)',
            data: [11, 32, 45, 32, 34, 52, 41, 60, 70, 85, 90, 105]
        }],
        chart: {
            height: 350,
            type: 'area', // Daha modern görünüm için 'area' seçtik
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        colors: ['#4680ff', '#f44236'], // Temaya uygun renkler
        xaxis: {
            categories: ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"]
        },
        tooltip: {
            x: { format: 'dd/MM/yy HH:mm' },
        },
    };

    var chart = new ApexCharts(document.querySelector("#stock-movement-chart"), options);
    chart.render();
});
</script>
<?= $this->endSection() ?>
