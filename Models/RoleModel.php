<?php

namespace App\Models;

class RoleModel extends BaseUuidModel
{
    protected $table         = 'roles';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['id','name','description','created_at','updated_at','deleted_at'];

    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
}