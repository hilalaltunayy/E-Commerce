<?php

use App\DTO\Admin\ChartPointDTO;

if (!function_exists('dash_fill_daily_series')) {
    /**
     * @param array<string,int|float> $map  ['2026-02-01' => 12, ...]
     * @return ChartPointDTO[]
     */
    function dash_fill_daily_series(array $map, int $days): array
    {
        $out = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $out[] = new ChartPointDTO($d, (float)($map[$d] ?? 0));
        }
        return $out;
    }
}