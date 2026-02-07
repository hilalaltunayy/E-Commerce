<?php 
namespace App\Models;
use CodeIgniter\Model;

class ProductsModel extends BaseUuidModel {
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true; // Soft delete özelliğini aç
    protected $deletedField  = 'deleted_at'; // Tarihin tutulacağı sütun
    protected $allowedFields = ['product_name', 'author', 'category_id', 'description', 
        'price', 'stock_count', 'type', 'image', 'is_active', 'deleted_at'];

    // Otomatik zaman damgalarını aktifleştiriyoruz
    protected $useTimestamps = true;

    
}