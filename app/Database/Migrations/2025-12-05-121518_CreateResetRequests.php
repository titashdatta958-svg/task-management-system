<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResetRequests extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'null' => false], // FK to users.id
            'email'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'status'      => ['type' => "ENUM('pending','approved','rejected','completed')", 'default' => 'pending'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('reset_requests');
    }

    public function down()
    {
        $this->forge->dropTable('reset_requests');
    }
}
