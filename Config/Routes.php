<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- HERKESİN ERİŞEBİLECEĞİ ROTALAR (Açık Alan) ---
$routes->get('/', 'Login::index'); 
$routes->get('login', 'Login::index'); 
$routes->post('login/auth', 'Login::auth');
$routes->get('register', 'Register::index');      
$routes->post('register/save', 'Register::save'); 
$routes->get('logout', 'Logout::index');

// --- SADECE GİRİŞ YAPANLARIN ERİŞEBİLECEĞİ ROTALAR (Korumalı Alan) ---
// 'auth' filtresi sayesinde isLoggedIn session'ı olmayanlar bu gruba sızamaz.
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard (Ana Sayfa)
    $routes->get('dashboard_anasayfa', 'Home::index');

    // Ürün Yönetimi
    $routes->get('products', 'ProductController::index');
    $routes->get('products/new', 'ProductController::new'); 
    $routes->get('products/detail/(:num)', 'ProductController::detail/$1'); 
    $routes->post('products/save', 'ProductController::save');
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1');
    
});

// --- ÜRÜN LİSTELEME VE FİLTRELEME ROTALARI ---

// 1. ADIM: EN SPESİFİK ROTA (Kategori ID'si içeren)
// Bu her zaman en üstte olmalı ki sistem 2. parametreyi (ID) yakalayabilsin.
$routes->get('products/list/(:any)/(:any)', 'ProductController::listByCategory/$1/$2');

// 2. ADIM: GENEL TİP ROTASI (Sadece basili/dijital)
// Eğer URL'de 2. parametre yoksa sistem buraya düşer.
$routes->get('products/list/(:any)', 'ProductController::listByType/$1');

// 3. ADIM: DİĞERLERİ
$routes->get('products/selection', 'ProductController::selection');
$routes->get('products', 'ProductController::index');

// Düzenleme formunu açan rota (GET)
$routes->get('products/edit/(:num)', 'ProductController::edit/$1');
// Formdan gelen verileri veritabanına kaydeden rota (POST)
$routes->post('products/update', 'ProductController::update');

$routes->get('products/stock-management', 'ProductController::stock_management');

$routes->get('orders', 'OrderController::index');
$routes->post('orders/create', 'OrderController::create');

$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
});

$routes->group('admin', ['filter' => 'role:admin,secretary'], function($routes) {
    $routes->get('orders', 'Admin\Orders::index');
});

$routes->get(
    'admin/products',
    'Admin\Products::index',
    ['filter' => 'role:admin,secretary|perm:manage_products']
);