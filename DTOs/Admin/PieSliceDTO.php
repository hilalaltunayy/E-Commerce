<?php

namespace App\DTO\Admin;

class PieSliceDTO
{
    public function __construct(
        public string $label,
        public float $percent,  // 0-100
        public float|int $value // sayısal değer (adet/ciro)
    ) {}
}