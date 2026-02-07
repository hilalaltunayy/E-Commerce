<?php
namespace App\DTOs;

class ProductDTO {
    public ?int $id;
    public string $product_name;
    public ?string $author;
    public $category_id;
    public ?string $description;
    public float $price;
    public ?int $stock;
    public string $type; // basili, dijital, paket
    public ?string $image;

        public function __construct(array $data) {
        // null coalescing (??) kullanarak veri yoksa boş string atayalım ki hata vermesin
        $this->id     = $data['id'] ?? null;
        $this->product_name   = $data['product_name'] ?? 'İsimsiz Ürün';
        $this->author = $data['author'] ?? 'Yazar Belirtilmemiş';
        $this->category_id  = $data['category_id'] ?? null;
        $this->description  = $data['description'] ?? null;
        $this->price  = (float)($data['price'] ?? 0);
        $this->type   = $data['type'] ?? 'basili';
        $this->image  = $data['image'] ?? 'no-book.jpg';

        // BURASI KRİTİK: Veritabanından stock_count olarak geliyor, biz stock olarak saklıyoruz
        // Hem dijital kontrolünü yapıyoruz hem de stock_count isimlendirmesini çözüyoruz
        $this->stock = ($data['type'] !== 'dijital') 
                    ? (int)($data['stock_count'] ?? ($data['stock'] ?? 0)) 
                    : 0; // Veritabanında NULL yerine 0 tutmak sorgularda kolaylık sağlar
                
        
        
    }
}