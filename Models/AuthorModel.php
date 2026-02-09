<?php

namespace App\Models;

class AuthorModel extends BaseUuidModel
{
    protected $table         = 'authors';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id','name','bio','created_at','updated_at','deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
}