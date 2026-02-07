<?php

namespace App\Controllers;
use App\Services\AuthService;

class Home extends BaseController
{
    public function index() {
        $db = \Config\Database::connect();
        
        // 1. Sadece Basılı Ürünlerin İstatistikleri
        $data['total_basili'] = $db->table('products')
                                ->where('type', 'basili')
                                ->where('deleted_at', null)
                                ->countAllResults();

        // 2. Kritik Stok (Stoku 5'ten az olan basılı kitaplar)
        $data['critical_stock'] = $db->table('products')
                                    ->where('type', 'basili')
                                    ->where('stock_count <', 5)
                                    ->where('deleted_at', null)
                                    ->get()->getResult();

        // 3. Kategori Dağılımı (Pasta Grafik İçin Veri)
        $data['chart_data'] = $db->table('products')
                                ->select('categories.category_name, COUNT(products.id) as count')
                                ->join('categories', 'categories.id = products.category_id')
                                ->where('products.type', 'basili')
                                ->groupBy('products.category_id')
                                ->get()->getResult();

        return view('dashboard_anasayfa', $data);
    }
}
