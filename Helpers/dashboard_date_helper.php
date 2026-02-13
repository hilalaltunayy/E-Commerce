<?php

if (!function_exists('dash_today_start')) {
    function dash_today_start(): string { return date('Y-m-d 00:00:00'); }
}
if (!function_exists('dash_today_end')) {
    function dash_today_end(): string { return date('Y-m-d 23:59:59'); }
}
if (!function_exists('dash_week_start')) {
    function dash_week_start(): string
    {
        $ts = strtotime('monday this week');
        return date('Y-m-d 00:00:00', $ts);
    }
}
if (!function_exists('dash_month_start')) {
    function dash_month_start(): string { return date('Y-m-01 00:00:00'); }
}
if (!function_exists('dash_yesterday_range')) {
    function dash_yesterday_range(): array
    {
        $start = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $end   = date('Y-m-d 23:59:59', strtotime('-1 day'));
        return [$start, $end];
    }
}