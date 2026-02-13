<?php

namespace App\Services\Admin;
helper(['dashboard_date', 'dashboard_series']);

use App\DTO\Admin\{
    AdminNoteDTO,
    AuditLogItemDTO,
    ChartPointDTO,
    DashboardDTO,
    MetricCardDTO,
    OrderListItemDTO,
    PieSliceDTO,
    RevenueRowDTO,
    RevenueTableDTO
};
use CodeIgniter\Database\BaseConnection;

class DashboardService
{
    public function __construct(private BaseConnection $db) {}

    public function getDashboard(): DashboardDTO
    {
        $totalOrders   = $this->countOrders();
        $todayOrders   = $this->countOrdersBetween(dash_today_start(), dash_today_end());
        $weekOrders    = $this->countOrdersBetween(dash_week_start(), dash_today_end());
        $pendingOrders = $this->countOrdersByStatus('pending');
        $orderCards = [
            new MetricCardDTO('Toplam Sipariş', $totalOrders),
            new MetricCardDTO('Bugün Sipariş', $todayOrders),
            new MetricCardDTO('Bu Hafta Sipariş', $weekOrders),
            new MetricCardDTO('Bekleyen Sipariş', $pendingOrders),
        ];
           
        $latestOrders = $this->getLatestOrders(5);
        $ordersLineSeries = $this->getOrdersDailySeries(14);
        $ordersBarSeries  = $ordersLineSeries;
        $revenueTable = $this->getRevenueTable();

        $visitCard = $this->getVisitCard();
        $visitsCompareSeries = $this->getVisitsCompareSeries(14);

        $topCategoryPie = $this->getTopCategoryPie(6);
        $topAuthors = $this->getTopAuthors(10);
        $topDigitalBooks = $this->getTopDigitalBooks(10);

        $newUserCards = $this->getNewUserCards();
        $latestLogs = $this->getLatestAuditLogs(15);
        $notes = $this->getAdminNotes(20);

        return new DashboardDTO(
            orderCards: $orderCards,
            ordersLineSeries: $ordersLineSeries,
            ordersBarSeries: $ordersBarSeries,
            latestOrders: $latestOrders,

            revenueTable: $revenueTable,

            visitCard: $visitCard,
            visitsCompareSeries: $visitsCompareSeries,

            topCategoryPie: $topCategoryPie,
            topAuthors: $topAuthors,
            topDigitalBooks: $topDigitalBooks,

            newUserCards: $newUserCards,

            latestLogs: $latestLogs,
            notes: $notes,
        );
    }

    /* -------------------------
     * ORDERS
     * ------------------------- */

    private function countOrders(): int
    {
        return (int)$this->db->table('orders')->countAllResults();
    }

    private function countOrdersBetween(string $start, string $end): int
    {
        return (int)$this->db->table('orders')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->countAllResults();
    }

    private function countOrdersByStatus(string $status): int
    {
        return (int)$this->db->table('orders')
            ->where('status', $status)
            ->countAllResults();
    }

    /**
     * @return OrderListItemDTO[]
     */
    private function getLatestOrders(int $limit = 5): array
    {
        // products join (opsiyonel): ürün adını göstermek için
        $rows = $this->db->table('orders o')
            ->select('o.id, o.customer_name, o.total_amount, o.status, o.created_at, p.product_name')
            ->join('products p', 'p.id = o.product_id', 'left')
            ->orderBy('o.id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(fn($r) => new OrderListItemDTO(
            id: (int)$r['id'],
            customerName: (string)($r['customer_name'] ?? ('Order #' . $r['id'])),
            totalAmount: (float)($r['total_amount'] ?? 0),
            status: (string)(($r['status'] ?? '-') . (isset($r['product_name']) ? ' • ' . $r['product_name'] : '')),
            createdAt: (string)($r['created_at'] ?? '-'),
        ), $rows);
    }

    /**
     * @return ChartPointDTO[]
     */
    private function getOrdersDailySeries(int $days = 14): array
    {
        $start = date('Y-m-d 00:00:00', strtotime("-" . ($days - 1) . " days"));
        $end   = dash_today_end();

        $rows = $this->db->table('orders')
            ->select('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->groupBy('DATE(created_at)')
            ->orderBy('d', 'ASC')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $r) {
            $map[$r['d']] = (int)$r['c'];
        }

        return dash_fill_daily_series($map, $days);
    }

    /* -------------------------
     * REVENUE
     * ------------------------- */

    private function getRevenueTable(): RevenueTableDTO
    {
        $daily  = $this->sumRevenueBetween(dash_today_start(), dash_today_end());
        $weekly = $this->sumRevenueBetween(dash_week_start(), dash_today_end());
        $monthly= $this->sumRevenueBetween(dash_month_start(), dash_today_end());

        $rows = [
            new RevenueRowDTO('Bugün', $daily),
            new RevenueRowDTO('Bu Hafta', $weekly),
            new RevenueRowDTO('Bu Ay', $monthly),
        ];

        $style = $this->getRevenueTableStyleFromSettings();
        return new RevenueTableDTO($rows, $style);
    }

    private function sumRevenueBetween(string $start, string $end): float
    {
        $row = $this->db->table('orders')
            ->select('COALESCE(SUM(total_amount),0) as total')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->where('status !=', 'cancelled')
            ->get()
            ->getRowArray();

        return (float)($row['total'] ?? 0);
    }

    private function getRevenueTableStyleFromSettings(): array
    {
        $defaults = [
            'headerBg' => '#111827',
            'headerText' => '#ffffff',
            'rowOddBg' => '#f3f4f6',
            'rowEvenBg' => '#ffffff',
        ];

        $rows = $this->db->table('admin_settings')
            ->select('setting_key, setting_value')
            ->like('setting_key', 'revenue_table_', 'after')
            ->get()
            ->getResultArray();

        if (!$rows) return $defaults;

        $style = $defaults;
        foreach ($rows as $r) {
            $k = (string)$r['setting_key'];
            $v = (string)$r['setting_value'];

            if ($k === 'revenue_table_header_bg') $style['headerBg'] = $v;
            if ($k === 'revenue_table_header_text') $style['headerText'] = $v;
            if ($k === 'revenue_table_row_odd_bg') $style['rowOddBg'] = $v;
            if ($k === 'revenue_table_row_even_bg') $style['rowEvenBg'] = $v;
        }
        return $style;
    }

    /* -------------------------
     * VISITS
     * ------------------------- */

    private function getVisitCard(): MetricCardDTO
    {
        $today = $this->countVisitsBetween(dash_today_start(), dash_today_end());
        [$yStart, $yEnd] = dash_yesterday_range();
        $yesterday = $this->countVisitsBetween($yStart, $yEnd);

        $deltaPct = null;
        $trend = 'flat';
        if ($yesterday > 0) {
            $deltaPct = (($today - $yesterday) / $yesterday) * 100;
            $trend = $deltaPct > 0 ? 'up' : ($deltaPct < 0 ? 'down' : 'flat');
        }

        return new MetricCardDTO(
            title: 'Site Ziyaret (Bugün)',
            value: $today,
            deltaPct: $deltaPct,
            trend: $trend,
            subtitle: 'Düne göre'
        );
    }

    private function countVisitsBetween(string $start, string $end): int
    {
        return (int)$this->db->table('visits')
            ->where('visited_at >=', $start)
            ->where('visited_at <=', $end)
            ->countAllResults();
    }

    /**
     * @return ChartPointDTO[]
     */
    private function getVisitsCompareSeries(int $days = 14): array
    {
        $start = date('Y-m-d 00:00:00', strtotime("-" . ($days - 1) . " days"));
        $end   = dash_today_end();

        $rows = $this->db->table('visits')
            ->select('DATE(visited_at) as d, COUNT(*) as c')
            ->where('visited_at >=', $start)
            ->where('visited_at <=', $end)
            ->groupBy('DATE(visited_at)')
            ->orderBy('d', 'ASC')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $r) $map[$r['d']] = (int)$r['c'];

        return dash_fill_daily_series($map, $days);
    }

    /* -------------------------
     * TOP CATEGORY PIE (orders üzerinden)
     * ------------------------- */

    /**
     * @return PieSliceDTO[]
     */
    private function getTopCategoryPie(int $limit = 6): array
    {
        $rows = $this->db->table('orders o')
            ->select('c.category_name as category_name, SUM(o.quantity) as qty')
            ->join('products p', 'p.id = o.product_id', 'left')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->groupBy('c.category_name')
            ->orderBy('qty', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $total = 0;
        foreach ($rows as $r) $total += (int)($r['qty'] ?? 0);
        if ($total <= 0) return [];

        $out = [];
        foreach ($rows as $r) {
            $qty = (int)($r['qty'] ?? 0);
            $out[] = new PieSliceDTO(
                label: (string)($r['category_name'] ?? 'Unknown'),
                percent: ($qty / $total) * 100,
                value: $qty
            );
        }
        return $out;
    }

    /**
     * @return array<int, array{label:string,value:int}>
     */
    private function getTopAuthors(int $limit = 10): array
    {
        $rows = $this->db->table('orders o')
            ->select('a.name as author_name, SUM(o.quantity) as qty')
            ->join('products p', 'p.id = o.product_id', 'left')
            ->join('authors a', 'a.id = p.author_id', 'left')
            ->groupBy('a.name')
            ->orderBy('qty', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(fn($r) => [
            'label' => (string)($r['author_name'] ?? 'Unknown'),
            'value' => (int)($r['qty'] ?? 0),
        ], $rows);
    }

    /**
     * Burada dijital tanımı sende net değil.
     * Şimdilik products.type = 'digital' varsayımıyla bıraktım.
     * @return array<int, array{label:string,value:int}>
     */
    private function getTopDigitalBooks(int $limit = 10): array
    {
        $rows = $this->db->table('orders o')
            ->select('p.product_name as title, SUM(o.quantity) as qty')
            ->join('products p', 'p.id = o.product_id', 'left')
            ->where('p.type', 'digital')
            ->groupBy('p.product_name')
            ->orderBy('qty', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(fn($r) => [
            'label' => (string)($r['title'] ?? 'Unknown'),
            'value' => (int)($r['qty'] ?? 0),
        ], $rows);
    }

    /* -------------------------
     * NEW USERS
     * ------------------------- */

    /**
     * @return MetricCardDTO[]
     */
    private function getNewUserCards(): array
    {
        $daily  = $this->countUsersBetween(dash_today_start(), dash_today_end());
        $weekly = $this->countUsersBetween(dash_week_start(), dash_today_end());
        $monthly= $this->countUsersBetween(dash_month_start(), dash_today_end());

        return [
            new MetricCardDTO('Yeni Kullanıcı (Bugün)', $daily),
            new MetricCardDTO('Yeni Kullanıcı (Bu Hafta)', $weekly),
            new MetricCardDTO('Yeni Kullanıcı (Bu Ay)', $monthly),
        ];
    }

    private function countUsersBetween(string $start, string $end): int
    {
        return (int)$this->db->table('users')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->countAllResults();
    }

    /* -------------------------
     * AUDIT LOGS
     * ------------------------- */

    /**
     * @return AuditLogItemDTO[]
     */
    private function getLatestAuditLogs(int $limit = 15): array
    {
        $rows = $this->db->table('audit_logs l')
            ->select('l.id, u.name as actor_name, l.actor_role, l.action, l.entity_type, l.entity_id, l.meta_json, l.created_at')
            ->join('users u', 'u.id = l.actor_id', 'left')
            ->orderBy('l.id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(fn($r) => new AuditLogItemDTO(
            id: (int)$r['id'],
            actorName: (string)($r['actor_name'] ?? 'System'),
            actorRole: (string)($r['actor_role'] ?? '-'),
            action: (string)($r['action'] ?? '-'),
            entityType: (string)($r['entity_type'] ?? '-'),
            entityId: isset($r['entity_id']) ? (string)$r['entity_id'] : null,
            createdAt: (string)($r['created_at'] ?? '-'),
            meta: isset($r['meta_json']) ? (string)$r['meta_json'] : null,
        ), $rows);
    }

    /* -------------------------
     * ADMIN NOTES
     * ------------------------- */

    /**
     * @return AdminNoteDTO[]
     */
    private function getAdminNotes(int $limit = 20): array
    {
        $rows = $this->db->table('admin_notes')
            ->select('id, admin_id, note, created_at, updated_at')
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(fn($r) => new AdminNoteDTO(
            id: (int)$r['id'],
            note: (string)($r['note'] ?? ''),
            createdAt: (string)($r['created_at'] ?? '-'),
            updatedAt: $r['updated_at'] ?? null
        ), $rows);
    }
}