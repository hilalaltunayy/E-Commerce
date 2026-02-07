<?php

namespace App\Services;

use App\Models\UserModel;
use App\DTOs\UserDTO;

class AuthService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Kayıt İşlemi Servisi
    public function registerUser(UserDTO $userDTO)
    {
        return $this->userModel->save([
            'username' => $userDTO->username,
            'email'    => $userDTO->email,
            'password' => $userDTO->password
        ]);
    }

    // Giriş Kontrol Servisi
    public function attemptLogin($email, $password)
    {
        $user = $this->userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}