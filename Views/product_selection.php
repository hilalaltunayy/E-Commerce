<?= $this->extend("layouts/main") ?>

<?= $this->section("content") ?>
<style>
    .selection-container {
        height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #F5F5DC; /* Ekru Arka Plan */
    }

    .type-card {
        background-color: #E67E22; /* Turuncu */
        color: white;
        width: 250px;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border-radius: 15px;
        font-weight: bold;
        font-size: 1.2rem;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        cursor: pointer;
        border: none;
    }

    .type-card:hover {
        background-color: #800000; /* Bordo */
        transform: scale(1.1); /* Büyüme Efekti */
        color: white;
        box-shadow: 0 12px 25px rgba(128, 0, 0, 0.3);
    }

    .type-card span {
        padding: 20px;
    }
</style>

<div class="pc-container">
    <div class="pc-content">
        <div class="selection-container">
            <div class="row g-4 justify-content-center w-100">
                <div class="col-auto">
                    <a href="<?= base_url('products/list/basili') ?>" class="type-card">
                        <span>BASILI ÜRÜNLER</span>
                    </a>
                </div>
                
                <div class="col-auto">
                    <a href="<?= base_url('products/list/dijital') ?>" class="type-card">
                        <span>DİJİTAL ÜRÜNLER</span>
                    </a>
                </div>
                
                <div class="col-auto">
                    <a href="<?= base_url('products/list/paket') ?>" class="type-card">
                        <span>BASILI-DİJİTAL ÜRÜNLER</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>