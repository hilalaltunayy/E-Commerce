<?php

namespace App\DTO\Admin;

class DashboardDTO
{
    /**
     * @param MetricCardDTO[] $orderCards
     * @param ChartPointDTO[] $ordersLineSeries
     * @param ChartPointDTO[] $ordersBarSeries
     * @param OrderListItemDTO[] $latestOrders
     * @param RevenueTableDTO $revenueTable
     * @param MetricCardDTO $visitCard
     * @param ChartPointDTO[] $visitsCompareSeries   // ör: bu hafta vs geçen hafta gibi
     * @param PieSliceDTO[] $topCategoryPie
     * @param array $topAuthors  // [{label, value}] gibi sade bırakıyorum
     * @param array $topDigitalBooks // [{label, value}]
     * @param MetricCardDTO[] $newUserCards
     * @param AuditLogItemDTO[] $latestLogs
     * @param AdminNoteDTO[] $notes
     */
    public function __construct(
        public array $orderCards,
        public array $ordersLineSeries,
        public array $ordersBarSeries,
        public array $latestOrders,

        public RevenueTableDTO $revenueTable,

        public MetricCardDTO $visitCard,
        public array $visitsCompareSeries,

        public array $topCategoryPie,
        public array $topAuthors,
        public array $topDigitalBooks,

        public array $newUserCards,

        public array $latestLogs,
        public array $notes,
    ) {}
}