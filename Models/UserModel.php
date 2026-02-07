<?php 
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends BaseUuidModel {
    protected $table = 'users';
    protected $allowedFields = ['name','email','password','role','status'];
}

//Veritabanı ile konuşacak model dosyası. 