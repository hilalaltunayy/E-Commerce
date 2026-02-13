<?php

namespace App\DTO\Admin;

class RevenueTableDTO
{
    /**
     * @param RevenueRowDTO[] $rows
     * @param array $style  ör: ['headerBg' => '#111827', 'headerText' => '#ffffff', 'rowOddBg' => '#f3f4f6']
     */
    public function __construct(
        public array $rows,
        public array $style = []
    ) {}
}