<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToTaskMembers extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'pending',
            ],
        ];
        $this->forge->addColumn('task_members', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('task_members', 'status');
    }
}
