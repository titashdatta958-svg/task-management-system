<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectManagementTables extends Migration
{
    public function up()
    {
    /**
         * projects table
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true, 
                'auto_increment' => true 
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true, 
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => false, 
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => false, 
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true); // true → means make it a primary key
        $this->forge->createTable('projects'); //Create a table named projects


        /**
         * members table
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('members');

        
        /**
         * tasks table
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'project_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => "ENUM('pending','in-progress','completed')",
                'default' => 'pending',
            ],
            'priority' => [
                'type' => "ENUM('low','medium','high')",
                'default' => 'medium',
            ],
            'due_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tasks');

        /**
         * task_members table
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'task_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'member_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            
           'status' => [
                'type' => "ENUM('pending','in-progress','completed')",
                'default' => 'pending',
            ],
            
           'completed_at' => [
           'type' => 'DATETIME',
           'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['task_id', 'member_id'], 'uq_task_member');
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE'); //CASCADE= If a project is deleted → all tasks belonging to that project are deleted.
        $this->forge->addForeignKey('member_id', 'members', 'id', 'CASCADE', 'CASCADE'); //CASCADE = automatically delete or update child records when the parent changes.
        $this->forge->createTable('task_members');
    
    }

    public function down()
    {
    
        $this->forge->dropTable('task_members', true); 
        $this->forge->dropTable('tasks', true);
        $this->forge->dropTable('members', true);
        $this->forge->dropTable('projects', true);
    }
}