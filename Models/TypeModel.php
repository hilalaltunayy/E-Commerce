<?php

namespace App\Models;

class TypeModel extends BaseUuidModel
{
    protected $table         = 'types';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id','name'
    ];

    // types tablosunda created_at vs yoksa:
    protected $useSoftDeletes = false;
    protected $useTimestamps  = false;
}