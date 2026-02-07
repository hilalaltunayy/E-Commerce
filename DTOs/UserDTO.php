<?php
namespace App\DTOs;

class UserDTO {
    public string $username;
    public string $email;
    public string $password;

    public function __construct(array $data) {
        // null coalescing (??) kullanarak veri yoksa boş string atayalım ki hata vermesin
        $this->username = $data['username'] ?? ''; 
        $this->email    = $data['email']    ?? '';
        
        // Şifre boşsa hashleme yaparken hata almamak için kontrol
        $rawPassword    = $data['password'] ?? '';
        $this->password = !empty($rawPassword) ? password_hash($rawPassword, PASSWORD_DEFAULT) : '';
    }
}