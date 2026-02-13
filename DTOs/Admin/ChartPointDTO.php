<?php

namespace App\DTO\Admin;

class ChartPointDTO
{
    public function __construct(
        public string $label,   // "2026-02-13" veya "Mon"
        public float $value
    ) {}
}