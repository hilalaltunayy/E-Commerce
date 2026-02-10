<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
{
    $user = session()->get('user') ?? [];
    return view('admin/dashboard', [
        'title' => 'Dashboard',
        'userEmail' => $user['email'] ?? '',
        'userRole'  => $user['role'] ?? '',
    ]);
}
}