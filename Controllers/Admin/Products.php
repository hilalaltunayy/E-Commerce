<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Products extends BaseController
{
    public function index()
    {
       $user = session()->get('user') ?? [];
    return view('admin/products/index', [
        'title' => 'Products',
        'userEmail' => $user['email'] ?? '',
        'userRole'  => $user['role'] ?? '',
    ]);
    }
}