<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\RoleModel;
use App\Models\RolePermissionModel;
use App\Models\PermissionModel;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user = $session->get('user');

        if (!$user) {
            return redirect()->to('/login');
        }

        $neededPerm = $arguments[0] ?? null; // perm:manage_orders -> ["manage_orders"]
        if (!$neededPerm) {
            return service('response')->setStatusCode(403, 'Forbidden');
        }

        // Session’da cache varsa kullan
        $cached = $session->get('permissions');
        if (is_array($cached) && in_array($neededPerm, $cached, true)) {
            return null;
        }

        $roleName = is_array($user) ? ($user['role'] ?? null) : null;
        if (!$roleName) {
            return service('response')->setStatusCode(403, 'Forbidden');
        }

        // DB’den role -> permissions çek
        $roleModel = new RoleModel();
        $rpModel   = new RolePermissionModel();
        $permModel = new PermissionModel();

        $roleRow = $roleModel->where('name', $roleName)->first();
        if (!$roleRow) {
            return service('response')->setStatusCode(403, 'Forbidden');
        }

        $roleId = $roleRow['id'];

        $permIds = $rpModel->where('role_id', $roleId)->findColumn('permission_id') ?? [];
        if (!$permIds) {
            $session->set('permissions', []);
            return service('response')->setStatusCode(403, 'Forbidden');
        }

        $permCodes = $permModel->whereIn('id', $permIds)->findColumn('code') ?? [];
        $session->set('permissions', $permCodes);

        if (!in_array($neededPerm, $permCodes, true)) {
            return service('response')->setStatusCode(403, 'Forbidden');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}