<?php namespace App\Models;

use CodeIgniter\Model;

class ResetRequestModel extends Model
{
    protected $table = 'reset_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','email','status','created_at'];
    protected $useTimestamps = false;
}
