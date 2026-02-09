<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;

class InitialAuthSeeder extends Seeder
{
    public function run()
    {
        $users = new UserModel();
        $roles = new RoleModel();
        $perms = new PermissionModel();
        $rp    = new RolePermissionModel();

        // 1) Roles
        $roleAdminId = $this->firstOrCreateRole($roles, 'admin', 'System administrator');
        $roleSecId   = $this->firstOrCreateRole($roles, 'secretary', 'Secretary');
        $roleUserId  = $this->firstOrCreateRole($roles, 'user', 'Regular user');

        // 2) Permissions (örnek)
        $permManageProductsId = $this->firstOrCreatePerm($perms, 'manage_products', 'Manage products');
        $permManageOrdersId   = $this->firstOrCreatePerm($perms, 'manage_orders', 'Manage orders');

        // 3) Role -> Permission (admin hepsine sahip olsun)
        $this->firstOrCreateRolePerm($rp, $roleAdminId, $permManageProductsId);
        $this->firstOrCreateRolePerm($rp, $roleAdminId, $permManageOrdersId);

        // secretary sadece orders
        $this->firstOrCreateRolePerm($rp, $roleSecId, $permManageOrdersId);

        // 4) Admin user (users.role sütununda string rol tutuyorsun)
        $existingAdmin = $users->where('email', 'admin@site.com')->first();
        if (!$existingAdmin) {
            $users->insert([
                'username' => 'admin',
                'email'    => 'admin@site.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role'     => 'admin',
                'status'   => 'active',
            ]);
        }
    }

    private function firstOrCreateRole(RoleModel $roles, string $name, string $desc): string
    {
        $row = $roles->where('name', $name)->first();
        if ($row) return (string) $row['id'];

        $roles->insert(['name' => $name, 'description' => $desc]);
        return (string) $roles->getInsertID(); // BaseUuidModel UUID üretiyor, insertID UUID gelir
    }

    private function firstOrCreatePerm(PermissionModel $perms, string $code, string $desc): string
    {
        $row = $perms->where('code', $code)->first();
        if ($row) return (string) $row['id'];

        $perms->insert(['code' => $code, 'description' => $desc]);
        return (string) $perms->getInsertID();
    }

    private function firstOrCreateRolePerm(RolePermissionModel $rp, string $roleId, string $permId): void
    {
        $row = $rp->where('role_id', $roleId)->where('permission_id', $permId)->first();
        if ($row) return;

        $rp->insert([
            'role_id'       => $roleId,
            'permission_id' => $permId,
        ]);
    }
}