<?= $this->extend("layouts/main") ?>

<?= $this->section("content") ?>
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <h3 class="fw-bold">📚 Yeni Kitap Ekle</h3>
            <hr>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="<?= base_url('products/save') ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kitap Adı</label>
                                    <input type="text" name="product_name" class="form-control" placeholder="Örn: Sefiller" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Yazar</label>
                                    <input type="text" name="author" class="form-control" placeholder="Örn: Victor Hugo" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Kategori Seçiniz...</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>"><?= esc($cat->category_name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fiyat (TL)</label>
                                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Ürün Türü</label>
                                    <select name="type" id="product_type" class="form-select" required onchange="toggleStock()">
                                        <option value="basili">Basılı Kitap</option>
                                        <option value="dijital">Dijital (E-Kitap)</option>
                                        <option value="paket">Paket (Dijital + Basılı)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3" id="stock_area">
                                <label class="form-label fw-bold">Stok Adedi</label>
                                <input type="number" name="stock" id="stock_input" class="form-control" value="0">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Kitap Açıklaması</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Kitap hakkında kısa bilgi..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('products') ?>" class="btn btn-light">Vazgeç</a>
                                <button type="submit" class="btn btn-warning fw-bold text-white px-5">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStock() {
    const type = document.getElementById('product_type').value;
    const stockArea = document.getElementById('stock_area');
    const stockInput = document.getElementById('stock_input');

    // Sadece dijital seçildiğinde stok gizlenir, paket veya basılıda görünür
    if (type === 'dijital') {
        stockArea.style.display = 'none';
        stockInput.value = 0; 
    } else {
        stockArea.style.display = 'block';
    }
}
// Sayfa yüklendiğinde kontrolü bir kez çalıştır
window.onload = toggleStock;
</script>
<?= $this->endSection() ?>