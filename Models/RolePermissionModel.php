<?php

namespace App\Models;

class RolePermissionModel extends BaseUuidModel
{
    protected $table         = 'role_permissions';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id','role_id','permission_id','created_at','updated_at','deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
}