<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * İstek işlenmeden önce (Before) çalışır.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Eğer kullanıcı giriş yapmamışsa (isLoggedIn session'ı yoksa)
        if (!session()->get('isLoggedIn')) {
            // Kullanıcıyı hata mesajıyla birlikte login sayfasına fırlat
            return redirect()->to(base_url('login'))->with('error', 'Lütfen önce giriş yapın.');
        }
    }

    /**
     * İstek işlendikten sonra (After) çalışır.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Genelde burada bir işlem yapmamıza gerek kalmaz.
    }
}