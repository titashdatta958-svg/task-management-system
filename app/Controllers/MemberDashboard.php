<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TasksMembersModel;
use App\Models\TaskModel;
use App\Models\ProjectModel;

class MemberDashboard extends Controller
{
    public function index()
    {
        if (! session()->get('logged_in') || session()->get('role') !== 'member') {
            return redirect()->to('/login')->with('error', 'Please login as member.');
        }

        $memberId = session()->get('member_id');

        $tm = new TasksMembersModel();
        $taskModel = new TaskModel();
        $projectModel = new ProjectModel();

        // Get assigned task rows (these have the member's status)
        $assignedRows = $tm->where('member_id', $memberId)->findAll();

        $tasks = [];
        foreach ($assignedRows as $r) {
            $task = $taskModel->find($r['task_id']);
            if (!$task) continue;
            $project = $projectModel->find($task['project_id']);
            $task['project_name'] = $project ? $project['name'] : '—';
            // Use the member's status from task_members, not the task status
            $task['status'] = $r['status'];
             // ADD THIS 🔥
            $task['completed_at'] = $r['completed_at'];
            // $task['due_date'] = $task['due_date'];  // already in tasks table

            $tasks[] = $task;
        }

        return view('member/dashboard', [
            'tasks' => $tasks
        ]);
    }
    
}