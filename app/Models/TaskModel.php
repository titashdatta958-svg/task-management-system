<?php
namespace App\Models;
use CodeIgniter\Model;

class TaskModel extends Model {
    
    protected $table      = 'tasks';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'project_id','title','description','status','priority','due_date','created_at',
        'updated_at', 'progress'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
