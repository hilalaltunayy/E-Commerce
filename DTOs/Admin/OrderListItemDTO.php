<?php

namespace App\DTO\Admin;

class OrderListItemDTO
{
    public function __construct(
        public int $id,
        public string $customerName,
        public float $totalAmount,
        public string $status,
        public string $createdAt
    ) {}
}