<?php

namespace App\Controllers;
use App\Services\AuthService;

class Logout extends BaseController
{
    public function index()
    {
        // 1. Tüm oturum (session) verilerini temizle
        session()->destroy();

        // 2. Kullanıcıyı login sayfasına yönlendir
        return redirect()->to(base_url('login'))->with('success', 'Başarıyla çıkış yapıldı.');
    }
}