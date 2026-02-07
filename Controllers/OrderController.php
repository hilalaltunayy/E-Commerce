<?php
// app/Controllers/OrderController.php

namespace App\Controllers;
use App\Services\ProductsService;

class OrderController extends BaseController {
    protected $productsService;

    public function __construct() {
        $this->productsService = new ProductsService();
    }

    public function index() {
        $db = \Config\Database::connect();
        // Basılı ürünleri çekelim ki sipariş oluştururken seçebilelim
        $data['products'] = $db->table('products')
                               ->where('type', 'basili')
                               ->where('is_active', 1)
                               ->get()->getResult();
                               
        // Geçmiş siparişleri çekelim
        $data['orders'] = $db->table('orders')
                             ->select('orders.*, products.product_name')
                             ->join('products', 'products.id = orders.product_id')
                             ->orderBy('order_date', 'DESC')
                             ->get()->getResult();

        return view('orders_view', $data);
    }


    public function create() {
            $db = \Config\Database::connect();
            
            // 1. Formdan gelen verileri al
            $productId = $this->request->getPost('product_id');
            $quantity  = $this->request->getPost('quantity');

            // 2. Ürün bilgilerini çek (Fiyat hesaplamak için)
            $product = $db->table('products')->where('id', $productId)->get()->getRow();

            if ($product && $product->stock_count >= $quantity) {
                $totalPrice = $product->price * $quantity;

                // 3. Siparişi Kaydet
                $db->table('orders')->insert([
                    'product_id'  => $productId,
                    'quantity'    => $quantity,
                    'total_price' => $totalPrice,
                    'order_date'  => date('Y-m-d H:i:s')
                ]);

                // 4. KRİTİK NOKTA: Stoktan düş 
                // Bu metot stok 0 olursa ürünü otomatik pasife alıyor zaten
                $this->productsService->reduceStock($productId, $quantity);

                return redirect()->to(base_url('orders'))->with('success', 'Sipariş başarıyla oluşturuldu, stok güncellendi.');
            }

            return redirect()->back()->with('error', 'Stok yetersiz veya ürün bulunamadı!');
        }
}
?>