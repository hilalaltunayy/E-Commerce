<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
/** bunu yazmazsan tableExits çalışmıyor 
 * @property \CodeIgniter\Database\BaseConnection $db
 */



class CreateVisitsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('visits')) return;

        $this->forge->addField([
            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'visited_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'session_id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => true
            ],
            'referrer' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
        ]);

        $this->forge->addKey('id', true); // PRIMARY KEY
        $this->forge->addKey('visited_at');
        $this->forge->addKey('user_id');

        $this->forge->createTable('visits', true);
    }

    public function down()
    {
        $this->forge->dropTable('visits', true);
    }
}