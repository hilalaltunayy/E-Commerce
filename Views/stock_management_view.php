<?= $this->extend("layouts/main") ?>
<?= $this->section("content") ?>

<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <h3 class="fw-bold"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#4a2b2b"><path d="M280-280h80v-280h-80v280Zm160 0h80v-400h-80v400Zm160 0h80v-160h-80v160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg> Stok Yönetim Paneli</h3>
            <hr>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-white"><h5>Kategori Dağılımı</h5></div>
                    <div class="card-body d-flex align-items-center">
                        <canvas id="stockPieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-dark text-white"><h5>Tüm Basılı Stoklar</h5></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kitap</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Mevcut Stok</th>
                                        <th class="text-center">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($basili_products as $row): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?= $row->product_name ?></span><br>
                                            <small class="text-muted"><?= $row->author ?></small>
                                        </td>
                                        <td><span class="badge bg-light-primary text-primary"><?= $row->category_name ?></span></td>
                                        <td class="text-center">
                                            <span class="badge <?= ($row->stock_count < 5) ? 'bg-danger' : 'bg-success' ?> fs-6">
                                                <?= $row->stock_count ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url('products/edit/'.$row->id) ?>" class="btn btn-sm btn-outline-dark">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('stockPieChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [<?php foreach($chart_data as $c) echo "'".$c->category_name."',"; ?>],
        datasets: [{
            data: [<?php foreach($chart_data as $c) echo $c->count.","; ?>],
            backgroundColor: ['#fd7e14', '#dc3545', '#052c65', '#20c997', '#6f42c1'],
            hoverOffset: 15
        }]
    },
    options: {
        animation: { duration: 2000, easing: 'easeOutBounce' },
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>

<?= $this->endSection() ?>