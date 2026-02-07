<?php namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        // Login sayfasını gösterir
        return view('login');
    }

    public function auth()
    {
        $session = session();
        $now = time();

        // 1. ADIM: Mevcut bir bekleme cezası var mı kontrol et
        $lastAttemptTime = $session->get('last_attempt_time') ?? 0;
        $waitTime = $session->get('current_wait_time') ?? 0;
        $remainingTime = ($lastAttemptTime + $waitTime) - $now;

        if ($remainingTime > 0) {
            return redirect()->back()->with('error', "Çok fazla hatalı deneme! Lütfen {$remainingTime} saniye bekleyin.");
        }

        // 2. ADIM: Form verilerini al
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $authService = new \App\Services\AuthService();

        // 3. ADIM: Giriş denemesi yap
        $user = $authService->attemptLogin($email, $password);

        if ($user) {
            // BAŞARILI: Tüm ceza sayaçlarını sıfırla ve içeri al
            $session->remove(['login_errors', 'last_attempt_time', 'current_wait_time']);
            $session->set('isLoggedIn', true);

            $session->set('userData', [
                'id'    => $user['id'],
                'name'  => $user['username'],
                'email' => $user['email']
            ]);

            // ✅ RoleFilter'ın beklediği alanlar
             $session->set('user_id', (string)$user['id']);
            $session->set('role', (string)$user['role']); // admin, secretary, user

            return redirect()->to(base_url('dashboard_anasayfa'));
        }

        // 4. ADIM: HATALI GİRİŞ - Cezalandırma Mantığı
        $errorCount = ($session->get('login_errors') ?? 0) + 1;
        $session->set('login_errors', $errorCount);
        $session->set('last_attempt_time', $now);

        // Kademeli bekleme süresini belirle
        $newWaitTime = 0;
        if ($errorCount == 3) {
            $newWaitTime = 30; // 3. hatada 30 saniye
        } elseif ($errorCount >= 4) {
            $newWaitTime = 60; // 4. ve sonraki her hatada 60 saniye
        }

        $session->set('current_wait_time', $newWaitTime);

        $errorMessage = 'Hatalı e-posta veya şifre!';
        if ($newWaitTime > 0) {
            $errorMessage = "Hatalı giriş! 3 deneme hakkınız doldu, {$newWaitTime} saniye engellendiniz.";
        }

        return redirect()->back()->with('error', $errorMessage);
    }
}