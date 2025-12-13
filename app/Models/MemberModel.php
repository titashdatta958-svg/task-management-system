<?php
namespace App\Models;
use CodeIgniter\Model;

class MemberModel extends Model {
    
    protected $table      = 'members';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','email'];
    protected $useTimestamps = true; // Enable automatic created_at and updated_at fields
}
