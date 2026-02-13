<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** bunu yazmazsan tableExits çalışmıyor 
 * @property \CodeIgniter\Database\BaseConnection $db
 */

class CreateAdminSettingsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('admin_settings')) return;

        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'CONSTRAİNT' => 36 ],
            'setting_key' => ['type' => 'VARCHAR', 'constraint' => 191],
            'setting_value' => ['type' => 'TEXT', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('setting_key', true); // unique
        $this->forge->createTable('admin_settings', true);

        // defaults
        $now = date('Y-m-d H:i:s');
        $defaults = [
            ['revenue_table_header_bg', '#111827'],
            ['revenue_table_header_text', '#ffffff'],
            ['revenue_table_row_odd_bg', '#f3f4f6'],
            ['revenue_table_row_even_bg', '#ffffff'],
        ];
        foreach ($defaults as [$k,$v]) {
            $this->db->table('admin_settings')->insert([
                'setting_key' => $k,
                'setting_value' => $v,
                'updated_at' => $now
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('admin_settings', true);
    }
}