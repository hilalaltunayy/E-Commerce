<?php
namespace App\Services;

use App\Models\ProductsModel;
use App\DTOs\ProductDTO;

class ProductsService {
    protected $model;

    public function __construct() {
        // Model katmanını burada başlatıyoruz
        $this->model = new ProductsModel();
    }

    /**
     * Aktif ürünleri getirir ve DTO paketlerine dönüştürür.
     */
    public function getActiveProducts(): array {
        //
        $results = $this->model->where('is_active', 1)->findAll();

        // Her bir veritabanı satırını bir ProductDTO nesnesine çeviriyoruz
        return array_map(function($item) {
            return new ProductDTO($item);
        }, $results);
    }

    public function getProductsByType(string $type): array
    {
        // 'all' butonu veya ilk giriş için genel liste
     
        return $this->getFilteredProducts($type, 'all');
        
    }


        /**Veritabanında 'type' sütununa göre filtreleme yapıyoruz
        Eğer tip 'paket' ise hem basılı hem dijital içeriği kapsar
        $results = $this->model->where('type', $type)
                            ->where('is_active', 1)
                            ->findAll();

        // Sonuçları DTO paketlerine dönüştürüp Controller'a tertemiz gönderiyoruz
        return array_map(function($item) {
            return new \App\DTOs\ProductDTO($item);
        }, $results);*/

    

    // Seçilen tipe göre (basili/dijital) kategorileri getirir
    // Kategorileri çeken fonksiyon
    public function getCategoriesByType(string $type) {
        $db = \Config\Database::connect();
        return $db->table('categories')
                  ->select('categories.id, categories.category_name')
                  ->join('products', 'products.category_id = categories.id')
                  ->where('products.type', $type)
                  ->groupBy('categories.id')
                  ->get()
                  ->getResult();
    }
    

    // Hem tip hem kategoriye göre filtreleme yapar [cite: 4, 6]
    // Filtreleme yapan ana fonksiyon
    public function getFilteredProducts(string $type, $categoryId = null): array {
        $builder = $this->model->where('type', $type)->where('is_active', 1);

        if ($categoryId !== null && $categoryId !== 'all') {
            $builder->where('category_id', (int)$categoryId);
        }

        // DEBUG: Sorguyu direkt burada durdurup görelim
         //dd($this->model->getLastQuery()->getQuery()); 

        $results = $builder->findAll();
        return array_map(fn($item) => new ProductDTO($item), $results);
    }

    public function saveProduct(ProductDTO $dto): bool {
        // DTO'dan gelen veriyi Model'in anlayacağı dizi formatına çeviriyoruz
        $data = [
            'product_name' => $dto->product_name,
            'author'       => $dto->author,
            'category_id'  => $dto->category_id,
            'description'  => $dto->description,
            'price'        => $dto->price,
            'stock_count'  => $dto->stock,
            'type'         => $dto->type,
            'is_active'    => 1 // Yeni eklenen ürün varsayılan olarak aktiftir
        ];
         

        // Model üzerinden kaydı yapıyoruz
        return $this->model->insert($data);
}

        public function updateProduct(ProductDTO $dto): bool {
            // 1. Veriyi hazırla
           $data = [
                'product_name' => $dto->product_name,
                'author'       => $dto->author,
                'price'        => $dto->price,
                'stock_count'  => $dto->stock,
                'description'  => $dto->description,
                'category_id'  => $dto->category_id,
                'updated_at'   => date('Y-m-d H:i:s')
            ];

            // MÜHENDİSLİK MANTIĞI: Stok bittiyse otomatik pasife al ve deleted_at damgala
            if ($dto->type !== 'dijital' && $dto->stock <= 0) {
                $data['is_active'] = 0;
                $data['deleted_at'] = date('Y-m-d H:i:s');
            } else {
                // Eğer stok tekrar girildiyse (0'dan büyükse) geri aktif edebiliriz
                $data['is_active'] = 1;
                $data['deleted_at'] = null;
            }

            return $this->model->update($dto->id, $data);
        }

        // MANUEL SATIŞTAN KALDIRMA
        public function removeFromSale(int $id): bool {
            return $this->model->update($id, [
            'is_active'  => 0,
            'deleted_at' => date('Y-m-d H:i:s') // Silinme tarihini damgala
            ]);
        }

        

        public function getProductById($id): ?\App\DTOs\ProductDTO {
            // 1. Veritabanında o ID'ye sahip ürünü buluyoruz
            $item = $this->model->find($id);

            // 2. Eğer ürün bulunamazsa null dön (Mühendislik koruması)
            if (!$item) {
                return null;
            }

            // 3. Bulunan ham veriyi DTO'ya çevirip tertemiz gönderiyoruz
            return new \App\DTOs\ProductDTO($item);
        }

        public function reduceStock(int $productId, int $quantity = 1): bool {
                $product = $this->model->find($productId);
                
                if ($product && $product['stock_count'] >= $quantity) {
                    $newStock = $product['stock_count'] - $quantity;
                    
                    // Stok düşümü yapılıyor
                    $updateData = ['stock_count' => $newStock];
                    
                    // Eğer stok 0 olduysa otomatik pasife al (Soft Delete)
                    if ($newStock <= 0 && $product['type'] !== 'dijital') {
                        $updateData['is_active'] = 0;
                        $updateData['deleted_at'] = date('Y-m-d H:i:s');
                    }
                    
                    return $this->model->update($productId, $updateData);
                }
                return false; // Stok yetersizse
            }
       

}
    

    