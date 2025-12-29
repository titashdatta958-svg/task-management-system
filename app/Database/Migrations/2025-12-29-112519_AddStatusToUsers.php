<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1, // Set default to 1 (active) or 0 (inactive)
                'after'      => 'password', // Optional: places it after the password column
            ],
        ];

        // This adds the column to the existing table without affecting current data
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // This allows you to roll back the change later if needed
        $this->forge->dropColumn('users', 'status');
    }
}