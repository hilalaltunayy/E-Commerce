<?= $this->extend("layouts/main") ?>

<?= $this->section("content") ?>
<div class="pc-container">
    <div class="pc-content">
        <h3 class="fw-bold">✏️ Kitap Güncelle: <?= esc($product->product_name) ?></h3>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="<?= base_url('products/update') ?>" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $product->id ?>">
                            <input type="hidden" name="type" value="<?= $product->type ?>">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kitap Adı</label>
                                    <input type="text" name="product_name" class="form-control" value="<?= esc($product->product_name) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Yazar</label>
                                    <input type="text" name="author" class="form-control" value="<?= esc($product->author) ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fiyat (TL)</label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $product->price ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Stok Adedi (stock_count)</label>
                                    <input type="number" name="stock" class="form-control" value="<?= $product->stock ?>" <?= ($product->type === 'dijital' ? 'readonly' : '') ?>>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Açıklama</label>
                                <textarea name="description" class="form-control" rows="4"><?= esc($product->description) ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= previous_url() ?>" class="btn btn-light">İptal</a>
                                <button type="submit" class="btn btn-success fw-bold px-5">Değişiklikleri Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>