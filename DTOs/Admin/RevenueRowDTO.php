<?php

namespace App\DTO\Admin;

class RevenueRowDTO
{
    public function __construct(
        public string $periodLabel, // "Today", "This Week", "This Month"
        public float $revenue
    ) {}
}