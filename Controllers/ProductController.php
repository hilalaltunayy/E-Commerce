<?php

namespace App\Controllers;

// Dışarıdaki sınıfları kullanabilmek için 'use' ifadelerini ekliyoruz
use App\Services\ProductsService;
use App\DTOs\ProductDTO;

class ProductController extends BaseController
{
    // Servis katmanını saklayacağımız değişken
    protected $productsService;

    /**
     * Constructor (Yapıcı Metot)
     * Hatayı düzeltmek için dışarıdan parametre almayı bıraktık.
     */
    public function __construct()
    {
        // Servisi manuel olarak burada oluşturuyoruz
        $this->productsService = new ProductsService();
    }

    /**
     * Ürünleri Listeleme Ekranı
     */
    public function index()
    {
        // Servis aracılığıyla tüm ürünleri çekiyoruz
        $products = $this->productsService->getActiveProducts();
        
        // Verileri view dosyasına (products_view.php) gönderiyoruz
        return view('products_view', [
            'products' => $products,
            'title'    => 'Kitap Dünyası | Tüm Kitaplar'
        ]);}

        public function detail($id)
    {
        // İleride buraya tekil ürün detay DTO'su gelecek
    }

    public function selection()
    {
        // Bu metod sadece tasarladığımız o 3 büyük butonu içeren sayfayı yükler
        return view('product_selection');
    }

    public function listByType($type)
    {
        // 1. Service'e gidip "Bana sadece bu tipteki ürünleri getir" diyoruz
        $products = $this->productsService->getProductsByType($type);
        $categories = $this->productsService->getCategoriesByType($type);

        // 2. Sayfa başlığını dinamik yapalım (Örn: Basılı Ürünler Koleksiyonu)
        $data = [
            'products'    => $products,
            'categories'  => $categories, // Bu satır butonların çıkmasını sağlar
            'selectedCat' => 'all',       // İlk girişte "TÜMÜ" aktif görünsün
            'type'        => $type,
            'title'       => ($type == 'basili' ? 'Basılı Kitaplar' : ($type == 'dijital' ? 'Dijital Kitaplar' : 'Ortak Paketler'))
        ];

        // Hatırlarsan products_view.php içinde kategori butonlarını ve kartları tasarlamıştık
        return view('products_view', $data);
    }

    public function listByCategory($type, $categoryId = null) {
            // 1. Kategorileri de çekiyoruz (Menüde görünmesi için)
            
        $categories = $this->productsService->getCategoriesByType($type);
        
        // 2. Filtrelenmiş ürünleri çek (Asıl kitap kartları burada geliyor!)
         $products = $this->productsService->getFilteredProducts($type, $categoryId);

        /*$data = [
            'products'    => $products,
            'categories'  => $categories, // EKSİKTİ: Eklendi
            'type'        => $type,
            'selectedCat' => 'all', // Varsayılan olarak 'all' yaptık ki hepsi listelensin
            'title'       => ($type == 'basili' ? 'Basılı Kitaplar' : ($type == 'dijital' ? 'Dijital Kitaplar' : 'Ortak Paketler'))
        ];

        return view('products_view', $data);*/
        return view('products_view', [
        'type'        => $type,
        'categories'  => $categories,
        'products'    => $products, // Service'den gelen dolu liste
        'selectedCat' => $categoryId,
        'title'       => ($type == 'basili' ? 'Basılı Kitaplar' : 'Dijital Kitaplar')
        ]);
    }

    //Formun içindeki "Kategori" açılır listesini (dropdown) doldurabilmek için
    //veritabanındaki tüm kategorileri çekip View'a göndermemiz gerekiyor.
            public function new()
        {
            // Veritabanındaki tüm kategorileri çekiyoruz (Dropdown için)
            $db = \Config\Database::connect();
            $categories = $db->table('categories')->get()->getResult();

            return view('product_create', [
                'categories' => $categories,
                'title'      => 'Yeni Ürün Ekle'
            ]);
        }

        public function save() {
            // 1. Formdan gelen tüm veriyi alıp DTO'ya paketliyoruz
            $productDTO = new \App\DTOs\ProductDTO($this->request->getPost());

            // 2. Service katmanına "Bunu kaydet" diyoruz
            if ($this->productsService->saveProduct($productDTO)) {
                // Başarılıysa ürün listesine dön ve başarı mesajı ver
                return redirect()->to(base_url('products/list/'.$productDTO->type.'/all'))
                                ->with('success', 'Kitap başarıyla eklendi!');
            }

            // Hata varsa geri dön
            return redirect()->back()->with('error', 'Kayıt sırasında bir hata oluştu.');
        }

        // Düzenleme formunu açar
        public function edit($id) {
            $product = $this->productsService->getProductById($id);
            // Eğer ürün yoksa hata mesajıyla listeye geri at
            if (!$product) {
                return redirect()->to(base_url('products'))->with('error', 'Kitap bulunamadı.');
            }
            $db = \Config\Database::connect();
            $categories = $db->table('categories')->get()->getResult();

            return view('product_edit', [
                'product'    => $product,
                'categories' => $categories,
                'title'      => 'Kitabı Güncelle'
            ]);
        }

        // Satıştan kaldır (Sil butonu için)
    public function delete($id)
    {
        // 1. Silinecek ürünün bilgilerini al (Geri döneceğimiz rotayı bilmek için)
        $product = $this->productsService->getProductById($id);

        if ($product) {
            // 2. Silme işlemini yap (is_active=0 ve deleted_at=now)
            $this->productsService->removeFromSale($id);

            // 3. KRİTİK NOKTA: Kullanıcıyı silme yaptığı kategoriye geri gönder!
            // Örn: products/list/basili/1 (Roman kategorisindeyse orada kalır)
            return redirect()->to(base_url("products/list/{$product->type}/{$product->category_id}"))
                            ->with('success', 'Ürün satıştan kaldırıldı.');
        }

        return redirect()->to(base_url('products'))->with('error', 'Ürün bulunamadı.');
    }

    public function update()
        {
            // 1. Gelen verileri DTO'ya paketle
            $dto = new \App\DTOs\ProductDTO($this->request->getPost());

            // 2. Service'e "Güncelle" de
            if ($this->productsService->updateProduct($dto)) {
                // 3. Başarılıysa yine aynı kategoriye geri dön (UX başarısı!)
                return redirect()->to(base_url("products/list/{$dto->type}/{$dto->category_id}"))
                                ->with('success', 'Kitap başarıyla güncellendi.');
            }
            return redirect()->back()->with('error', 'Güncelleme sırasında bir hata oluştu.');
        }

        public function stock_management() {
            $db = \Config\Database::connect();
            
            // Sadece Basılı Ürünlerin Detaylı Stok Listesi
            $data['basili_products'] = $db->table('products')
                                        ->select('products.*, categories.category_name')
                                        ->join('categories', 'categories.id = products.category_id')
                                        ->where('products.type', 'basili')
                                        ->where('products.deleted_at', null)
                                        ->get()->getResult();

            // Grafik için kategori bazlı sayılar (Basılı ürünler için)
            $data['chart_data'] = $db->table('products')
                                    ->select('categories.category_name, COUNT(products.id) as count')
                                    ->join('categories', 'categories.id = products.category_id')
                                    ->where('products.type', 'basili')
                                    ->groupBy('products.category_id')
                                    ->get()->getResult();

            $data['title'] = "Stok Yönetim Paneli";

            return view('stock_management_view', $data);
        }

}