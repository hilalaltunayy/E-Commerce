<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
/** bunu yazmazsan tableExits çalışmıyor 
 * @property \CodeIgniter\Database\BaseConnection $db
 */


class AddDashboardColumnsToOrders extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('customer_name', 'orders')) {
            $fields['customer_name'] = [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
            ];
        }

        if (!$this->db->fieldExists('total_amount', 'orders')) {
            $fields['total_amount'] = [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ];
        }

        if (!$this->db->fieldExists('status', 'orders')) {
            $fields['status'] = [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'pending',
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('orders', $fields);
        }

        // total_amount'ı mevcut total_price ile doldur (tek seferlik)
        if ($this->db->fieldExists('total_amount', 'orders') && $this->db->fieldExists('total_price', 'orders')) {
            $this->db->query("UPDATE orders SET total_amount = total_price WHERE total_amount = 0 OR total_amount IS NULL");
        }
    }

    public function down()
    {
        $drop = [];
        foreach (['customer_name', 'total_amount', 'status'] as $col) {
            if ($this->db->fieldExists($col, 'orders')) $drop[] = $col;
        }
        if ($drop) $this->forge->dropColumn('orders', $drop);
    }
}