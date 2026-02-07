<?= $this->extend("layouts/main") ?>

<?= $this->section("content") ?>
<?php 
    // Mühendislik Koruması: Değişkenler yoksa varsayılanları kullan
    $title       = $title ?? 'Kitap Koleksiyonu';
    $currentType = $type ?? 'basili';
    $currentCat  = $selectedCat ?? null;
    $bookList    = $products ?? [];
    $categories  = $categories ?? [];
?>
<style>
    .pc-container { background-color: #F5F5DC !important; } 
    .category-btn {
        background-color: #E67E22; color: white; border-radius: 25px;
        transition: all 0.3s ease; border: none; font-weight: 600; min-width: 120px;
    }
    .category-btn:hover, .active-bordo {
        transform: scale(1.1); background-color: #800000 !important; color: white !important;
    }
    .book-card { border: none; border-radius: 15px; transition: 0.3s; background: #fff; }
    .book-card:hover { box-shadow: 0 10px 20px rgba(128, 0, 0, 0.15) !important; transform: translateY(-5px); }
    .price-tag { color: #E67E22; font-size: 1.2rem; font-weight: bold; }
    .author-name { color: #800000; font-style: italic; }
    .btn-turuncu { background-color: #E67E22; color: white; border-radius: 8px; }
</style>

<div class="pc-container">
    <div class="pc-content">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold" style="color: #2c3e50;"><?= esc($title) ?></h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('products/selection') ?>" style="color: #E67E22; font-weight: bold;">Ürün Seçimi</a></li>
                        <li class="breadcrumb-item active" style="color: #800000;"><?= ucfirst(esc($currentType)) ?></li>
                    </ol>
                </nav>
            </div>
            <a href="<?= base_url('products/new') ?>" class="btn btn-turuncu shadow">
               <i class="ti ti-plus"></i> Yeni Kitap Ekle 
            </a>
        </div>

        <div class="row mb-5 justify-content-center g-3">
            
            <div class="col-auto">
                <a href="<?= base_url("products/list/$currentType/all") ?>" 
                class="btn category-btn shadow-sm <?= ($currentCat === 'all') ? 'active-bordo' : '' ?>" 
                style="background-color: #2c3e50;">TÜMÜ</a>
            </div>

            <?php if(!empty($categories)): ?>
                <?php foreach($categories as $cat): ?>
                    <div class="col-auto">
                        <a href="<?= base_url("products/list/$currentType/".$cat->id) ?>" 
                        class="btn category-btn shadow-sm <?= ($currentCat == $cat->id) ? 'active-bordo' : '' ?>">
                        <?= esc($cat->category_name) ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

</div>

        <hr>

        <div class="row mt-4">
            <?php if($currentCat === null): ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Lütfen önce listelemek istediğiniz kategoriyi yukarıdan seçin.</h4>
                </div>
            <?php elseif(empty($bookList)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Bu kategoride henüz bir kitap bulunamadı.</p>
                </div>
            <?php else: ?>
                <?php foreach($bookList as $product): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card book-card h-100 shadow-sm">
                            <div class="text-center p-3">
                                <img src="<?= base_url('assets/images/books/' . esc($product->image ?? 'no-book.jpg')) ?>" class="img-fluid rounded" style="max-height: 180px;" alt="<?= esc($product->product_name) ?>">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-bold mb-1 text-dark"><?= esc($product->product_name) ?></h5>
                                <p class="author-name small mb-3"><?= esc($product->author ?? 'Yazar Bilinmiyor') ?></p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="price-tag"><?= number_format($product->price, 2) ?> TL</span>
                                        <?php if($product->type !== 'dijital'): ?>
                                            <span class="badge bg-light text-dark border">Stok: <?= esc($product->stock ?? 0) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="<?= base_url('products/edit/'.$product->id) ?>" class="btn btn-outline-secondary btn-sm">Düzenle</a>
                                        <a href="<?= base_url('products/delete/'.$product->id) ?>" class="btn btn-link text-danger btn-sm" onclick="return confirm('Bu kitabı silmek istediğinize emin misiniz?')">Satıştan Kaldır</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>