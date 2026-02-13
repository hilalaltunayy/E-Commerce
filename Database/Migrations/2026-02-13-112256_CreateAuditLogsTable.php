<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** bunu yazmazsan tableExits çalışmıyor 
 * @property \CodeIgniter\Database\BaseConnection $db
 */

class CreateAuditLogsTable extends Migration
{
     
    public function up()
    {
        if ($this->db->tableExists('audit_logs')) return;

        $this->forge->addField(fields: [
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'actor_id' => ['type' => 'INT', 'null' => true],
            'actor_role' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => 100],
            'entity_type' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'entity_id' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'meta_json' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('actor_id');
        $this->forge->createTable('audit_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs', true);
    }
}