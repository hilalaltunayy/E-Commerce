<?php

namespace App\Models;

class CategoryModel extends BaseUuidModel
{
    protected $table         = 'categories';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id','category_name'
    ];

    // categories tablosunda created_at vs yoksa timestamps kapalı kalsın:
    protected $useSoftDeletes = false;
    protected $useTimestamps  = false;
}