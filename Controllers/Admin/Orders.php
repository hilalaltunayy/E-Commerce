<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Orders extends BaseController
{
    public function index()
    {
       $user = session()->get('user') ?? [];
    return view('admin/orders/index', [
        'title' => 'Orders',
        'userEmail' => $user['email'] ?? '',
        'userRole'  => $user['role'] ?? '',
    ]);
    }
}