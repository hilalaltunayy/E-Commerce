<?php

namespace App\Services\Admin;

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
    public function __construct(
        private BaseConnection $db
    ) {}

    public function getDashboard(): DashboardDTO
    {
        // 1) Sipariş metrikleri
        $totalOrders   = $this->countOrders();
        $todayOrders   = $this->countOrdersBetween($this->todayStart(), $this->todayEnd());
        $weekOrders    = $this->countOrdersBetween($this->weekStart(), $this->todayEnd());
        $pendingOrders = $this->countOrdersByStatus('pending');

        $orderCards = [
            new MetricCardDTO('Toplam Sipariş', $totalOrders),
            new MetricCardDTO('Bugün Sipariş', $todayOrders),
            new MetricCardDTO('Bu Hafta Sipariş', $weekOrders),
            new MetricCardDTO('Bekleyen Sipariş', $pendingOrders),
        ];

        // 2) Son 5 sipariş
        $latestOrders = $this->getLatestOrders(5);

        // 3) Grafikler: sipariş çizgi + bar (ör: son 14 gün günlük)
        $ordersLineSeries = $this->getOrdersDailySeries(14);
        $ordersBarSeries  = $this->getOrdersDailySeries(14); // aynı seriyle başlayalım, view’da bar/line seçersin

        // 4) Ciro tablosu (günlük/haftalık/aylık)
        $revenueTable = $this->getRevenueTable();

        // 5) Ziyaret sayısı kartı + karşılaştırmalı seri
        $visitCard = $this->getVisitCard();
        $visitsCompareSeries = $this->getVisitsCompareSeries(14); // son 14 gün

        // 6) En çok hangi kategoride satış (% pie)
        $topCategoryPie = $this->getTopCategoryPie(6);

        // 7) En çok hangi yazar
        $topAuthors = $this->getTopAuthors(10);

        // 8) En çok hangi dijital kitap satılmış
        $topDigitalBooks = $this->getTopDigitalBooks(10);

        // 9) Yeni kullanıcı (günlük/haftalık/aylık)
        $newUserCards = $this->getNewUserCards();

        // 10) Log tablosu (son güncellemeler)
        $latestLogs = $this->getLatestAuditLogs(15);

        // 11) Notlarım
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
        return (int) $this->db->table('orders')->countAllResults();
    }

    private function countOrdersBetween(string $start, string $end): int
    {
        return (int) $this->db->table('orders')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->countAllResults();
    }

    private function countOrdersByStatus(string $status): int
    {
        return (int) $this->db->table('orders')
            ->where('status', $status)
            ->countAllResults();
    }

    /**
     * @return OrderListItemDTO[]
     */
    private function getLatestOrders(int $limit = 5): array
    {
        $rows = $this->db->table('orders')
            ->select('id, customer_name, total_amount, status, created_at')
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(fn($r) => new OrderListItemDTO(
            id: (int)$r['id'],
            customerName: (string)($r['customer_name'] ?? '-'),
            totalAmount: (float)($r['total_amount'] ?? 0),
            status: (string)($r['status'] ?? '-'),
            createdAt: (string)($r['created_at'] ?? '-'),
        ), $rows);
    }

    /**
     * Son N gün için günlük sipariş adedi serisi
     * @return ChartPointDTO[]
     */
    private function getOrdersDailySeries(int $days = 14): array
    {
        $start = date('Y-m-d 00:00:00', strtotime("-" . ($days - 1) . " days"));
        $end   = $this->todayEnd();

        // MySQL varsayımı: DATE(created_at)
        $rows = $this->db->table('orders')
            ->select('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->groupBy('DATE(created_at)')
            ->orderBy('d', 'ASC')
            ->get()
            ->getResultArray();

        // boş günleri de dolduralım
        $map = [];
        foreach ($rows as $r) {
            $map[$r['d']] = (int)$r['c'];
        }

        $series = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $series[] = new ChartPointDTO($d, (float)($map[$d] ?? 0));
        }
        return $series;
    }

    /* -------------------------
     * REVENUE
     * ------------------------- */

    private function getRevenueTable(): RevenueTableDTO
    {
        $daily  = $this->sumRevenueBetween($this->todayStart(), $this->todayEnd());
        $weekly = $this->sumRevenueBetween($this->weekStart(), $this->todayEnd());
        $monthly= $this->sumRevenueBetween($this->monthStart(), $this->todayEnd());

        $rows = [
            new RevenueRowDTO('Bugün', $daily),
            new RevenueRowDTO('Bu Hafta', $weekly),
            new RevenueRowDTO('Bu Ay', $monthly),
        ];

        // Stil: admin ayarlayabilsin diye settings tablosundan okuyacağız.
        $style = $this->getRevenueTableStyleFromSettings();

        return new RevenueTableDTO($rows, $style);
    }

    private function sumRevenueBetween(string $start, string $end): float
    {
        $row = $this->db->table('orders')
            ->select('COALESCE(SUM(total_amount),0) as total')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->where('status !=', 'cancelled') // örnek: iptali hariç tutmak istersen
            ->get()
            ->getRowArray();

        return (float)($row['total'] ?? 0);
    }

    private function getRevenueTableStyleFromSettings(): array
    {
        // Önerilen tablo: admin_settings (key, value)
        // key ör: revenue_table_header_bg, revenue_table_header_text, revenue_table_row_odd_bg ...
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

            // mapping
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
        // Önerilen tablo: visits (id, visited_at, path, user_id nullable, session_id, referrer, ...)
        $today = $this->countVisitsBetween($this->todayStart(), $this->todayEnd());
        $yesterdayStart = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $yesterdayEnd   = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $yesterday = $this->countVisitsBetween($yesterdayStart, $yesterdayEnd);

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
        return (int) $this->db->table('visits')
            ->where('visited_at >=', $start)
            ->where('visited_at <=', $end)
            ->countAllResults();
    }

    /**
     * @return ChartPointDTO[] (ör: bugün serisi / önceki dönem serisi gibi view’da kıyaslayabilirsin)
     */
    private function getVisitsCompareSeries(int $days = 14): array
    {
        // Basit hali: son N gün günlük ziyaret sayısı
        $start = date('Y-m-d 00:00:00', strtotime("-" . ($days - 1) . " days"));
        $end   = $this->todayEnd();

        $rows = $this->db->table('visits')
            ->select('DATE(visited_at) as d, COUNT(*) as c')
            ->where('visited_at >=', $start)
            ->where('visited_at <=', $end)
            ->groupBy('DATE(visited_at)')
            ->orderBy('d', 'ASC')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $r) {
            $map[$r['d']] = (int)$r['c'];
        }

        $series = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $series[] = new ChartPointDTO($d, (float)($map[$d] ?? 0));
        }
        return $series;
    }

    /* -------------------------
     * TOP CATEGORY PIE
     * ------------------------- */

    /**
     * Varsayım:
     * order_items: (order_id, product_id, quantity, unit_price)
     * products: (id, category_id, author_id, is_digital)
     * categories: (id, name)
     *
     * @return PieSliceDTO[]
     */
    private function getTopCategoryPie(int $limit = 6): array
    {
        // En çok satılan kategori: quantity toplamı üzerinden
        $rows = $this->db->table('order_items oi')
            ->select('c.name as category_name, SUM(oi.quantity) as qty')
            ->join('products p', 'p.id = oi.product_id')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->groupBy('c.name')
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

    /* -------------------------
     * TOP AUTHORS
     * ------------------------- */

    /**
     * authors: (id, name)
     * @return array<int, array{label:string,value:int}>
     */
    private function getTopAuthors(int $limit = 10): array
    {
        $rows = $this->db->table('order_items oi')
            ->select('a.name as author_name, SUM(oi.quantity) as qty')
            ->join('products p', 'p.id = oi.product_id')
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

    /* -------------------------
     * TOP DIGITAL BOOKS
     * ------------------------- */

    /**
     * products: (id, title, is_digital)
     * @return array<int, array{label:string,value:int}>
     */
    private function getTopDigitalBooks(int $limit = 10): array
    {
        $rows = $this->db->table('order_items oi')
            ->select('p.title as title, SUM(oi.quantity) as qty')
            ->join('products p', 'p.id = oi.product_id')
            ->where('p.is_digital', 1)
            ->groupBy('p.title')
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
     * users: (id, created_at, ...)
     * @return MetricCardDTO[]
     */
    private function getNewUserCards(): array
    {
        $daily  = $this->countUsersBetween($this->todayStart(), $this->todayEnd());
        $weekly = $this->countUsersBetween($this->weekStart(), $this->todayEnd());
        $monthly= $this->countUsersBetween($this->monthStart(), $this->todayEnd());

        return [
            new MetricCardDTO('Yeni Kullanıcı (Bugün)', $daily),
            new MetricCardDTO('Yeni Kullanıcı (Bu Hafta)', $weekly),
            new MetricCardDTO('Yeni Kullanıcı (Bu Ay)', $monthly),
        ];
    }

    private function countUsersBetween(string $start, string $end): int
    {
        return (int) $this->db->table('users')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->countAllResults();
    }

    /* -------------------------
     * AUDIT LOGS (Son güncellemeler)
     * ------------------------- */

    /**
     * audit_logs: (id, actor_id, actor_role, action, entity_type, entity_id, meta_json, created_at)
     * users: (id, name)
     *
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
     * admin_notes: (id, admin_id, note, created_at, updated_at)
     * @return AdminNoteDTO[]
     */
    private function getAdminNotes(int $limit = 20): array
    {
        // Şimdilik: admin_id filtrelemesini controller’dan session ile geçeriz.
        // Burada "tüm notlar" gibi bıraktım.
        $rows = $this->db->table('admin_notes')
            ->select('id, note, created_at, updated_at')
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

    /* -------------------------
     * DATE HELPERS
     * ------------------------- */

    private function todayStart(): string { return date('Y-m-d 00:00:00'); }
    private function todayEnd(): string   { return date('Y-m-d 23:59:59'); }

    private function weekStart(): string
    {
        // Pazartesi başlangıç (TR için mantıklı)
        $ts = strtotime('monday this week');
        return date('Y-m-d 00:00:00', $ts);
    }

    private function monthStart(): string
    {
        return date('Y-m-01 00:00:00');
    }
}