<?= $this->extend("layouts/main") ?>
<?= $this->section("content") ?>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                        <h6>Toplam Basılı Kitap</h6>
                        <h2><?= $total_basili ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-white"><h5>Kategori Bazlı Dağılım (Basılı)</h5></div>
                    <div class="card-body">
                        <canvas id="categoryChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <h5 class="text-white mb-0">📉 Kritik Stok Takibi (< 5 Adet)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kitap</th>
                                    <th>Yazar</th>
                                    <th>Mevcut Stok</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($critical_stock as $item): ?>
                                <tr class="table-danger">
                                    <td><?= $item->product_name ?></td>
                                    <td><?= $item->author ?></td>
                                    <td><strong><?= $item->stock_count ?></strong></td>
                                    <td>
                                        <a href="<?= base_url('products/edit/'.$item->id) ?>" class="btn btn-sm btn-dark">Stok Arttır</a>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('categoryChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut', // Pasta grafik
    data: {
        labels: [<?php foreach($chart_data as $c) echo "'".$c->category_name."',"; ?>],
        datasets: [{
            data: [<?php foreach($chart_data as $c) echo $c->count.","; ?>],
            backgroundColor: ['#e67e22', '#c0392b', '#2c3e50', '#f1c40f'], // Tema renklerin
            borderWidth: 0
        }]
    },
    options: {
        animation: { animateScale: true, animateRotate: true }, // Animasyonlu giriş
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
<?= $this->endSection() ?>