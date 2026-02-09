<?php

namespace App\Models;

class PermissionModel extends BaseUuidModel
{
    protected $table         = 'permissions';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id','code','description','created_at','updated_at','deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
}