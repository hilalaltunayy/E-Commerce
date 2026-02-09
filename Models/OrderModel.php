<?php

namespace App\Models;

class OrderModel extends BaseUuidModel
{
    protected $table         = 'orders';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id',
        'product_id',
        'quantity',
        'total_price',
        'order_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
}