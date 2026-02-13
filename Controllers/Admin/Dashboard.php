<?php

namespace App\Controllers\Admin;

use App\Services\Admin\DashboardService;
use App\Controllers\BaseController;


class Dashboard extends BaseController
{
     public function index()
    {
        $service = new DashboardService(db_connect());
        $dto = $service->getDashboard();

        return view('admin/dashboard', [
            'dto' => $dto
        ]);
    }
}