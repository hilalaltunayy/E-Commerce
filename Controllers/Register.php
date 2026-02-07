<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\DTOs\UserDTO;
use App\Services\AuthService;

class Register extends BaseController
{
    // Kayıt sayfasını (View) ekrana getiren fonksiyon
    public function index()
    {
        return view('register'); // Birazdan bu View dosyasını oluşturacağız
    }

    // Kayıt işlemini gerçekleştiren fonksiyon
    public function save()
    {
        // 1. Formdan gelen verileri alıp DTO paketine koyuyoruz
       // DTO şifreyi otomatik hash'liyor.
        $userDTO = new \App\DTOs\UserDTO($this->request->getPost());
        // 2. Modeli çağırıyoruz
        // $userModel = new UserModel
        // 2. İş Mantığı için Servis katmanını çağırıyoruz
        $authService = new \App\Services\AuthService();

        // 3. Veritabanına kaydı atıyoruz
        /*$userModel->save([
            'username' => $userDTO->username,
            'email'    => $userDTO->email,
            'password' => $userDTO->password
        ]);*/
    // 3. Kayıt işlemini Servise devrediyoruz
    // Servis arka planda Model ile konuşup veriyi kaydedecek.
       if ($authService->registerUser($userDTO)) {
        // 4. İşlem bitince kullanıcıyı login sayfasına yönlendirip mesaj verelim
        return redirect()->to(base_url('login'))->with('success', 'Kayıt başarılı! Giriş yapabilirsiniz.');
    } else {
        // Hata durumunda geri gönder
        return redirect()->back()->with('error', 'Kayıt sırasında bir hata oluştu.');
    }
    }
}