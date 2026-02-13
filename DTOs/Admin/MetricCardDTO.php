<?php

namespace App\DTO\Admin;

class MetricCardDTO
{
    public function __construct(
        public string $title,
        public float|int $value,
        public ?float $deltaPct = null,      // +12.5 / -3.2 gibi
        public ?string $trend = null,        // "up" | "down" | "flat"
        public ?string $subtitle = null
    ) {}
}