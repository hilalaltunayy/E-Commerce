<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** bunu yazmazsan tableExits çalışmıyor 
 * @property \CodeIgniter\Database\BaseConnection $db
 */

class CreateAdminNotesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('admin_notes')) return;

        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'admin_id' => ['type' => 'INT', 'null' => false],
            'note' => ['type' => 'TEXT', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('admin_id');
        $this->forge->addKey('created_at');
        $this->forge->createTable('admin_notes', true);
    }

    public function down()
    {
        $this->forge->dropTable('admin_notes', true);
    }
}