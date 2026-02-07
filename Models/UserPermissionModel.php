<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPermissionModel extends BaseUuidModel
{
    protected $table            = 'user_permissions';
    protected $primaryKey       = 'id'; // sende yoksa kaldır; ama sende pivot tablo id yoktuysa bunu null yapma
    protected $returnType       = 'array';
    protected $allowedFields = ['id','user_id','permission_id','allowed','created_at','updated_at','deleted_at'];
    public    $useTimestamps    = false;
    protected $useSoftDeletes = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';

    /**
     * permissionCode ile allowed kontrolü
     * - permissions tablosundan code -> id bulur
     * - user_permissions.allowed=1 ise true
     */
        public function isAllowed(string $userId, string $permCode): bool
    {
        // permissions.code üzerinden permission_id bulup user_permissions ile eşleştiriyoruz
        $row = $this->db->table('user_permissions up')
            ->select('up.allowed')
            ->join('permissions p', 'p.id = up.permission_id', 'inner')
            ->where('up.user_id', $userId)
            ->where('p.code', $permCode)
            ->where('up.deleted_at', null) // soft delete kullanıyorsan
            ->get()
            ->getRowArray();

        if (!$row) {
            return false;
        }

        return (int)$row['allowed'] === 1;
    }
}