<?php namespace App\Models;
use CodeIgniter\Model;

class TasksMembersModel extends Model {
    protected $table      = 'task_members';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id','member_id','status','completed_at'];
    protected $useTimestamps = false;

    public function getCompletionPercentage($taskId)
    {
        if (!$taskId) return 0;

        $total = $this->where('task_id', $taskId)->countAllResults();
        if ($total == 0) return 0;

        $completed = $this->where([
            'task_id' => $taskId,
            'status'  => 'completed'
        ])->countAllResults();

        return round(($completed / $total) * 100);
    }

    public function recalculateProgress($taskId)
    {
        if (!$taskId) {
            return 0;
        }

        $db = \Config\Database::connect();

        $total = $db->table('task_members')
                    ->where('task_id', $taskId)
                    ->countAllResults();

        $completed = $db->table('task_members')
                        ->where('task_id', $taskId)
                        ->where('status', 'completed')
                        ->countAllResults();

        $progress = ($total > 0) ? round(($completed / $total) * 100) : 0;

        $taskModel = new \App\Models\TaskModel();
        $taskExists = $taskModel->find($taskId);

        if ($taskExists) {
            $taskModel->update($taskId, ['progress' => $progress]);
        }

        return $progress;
    }

    public function updateTaskProgress($taskId)
    {
        return $this->recalculateProgress($taskId);

    }



    

    public function markMemberCompleted($taskId, $memberId)
{
    // Mark member as completed (use where/update for compatibility)
   $this->where('task_id', $taskId)
     ->where('member_id', $memberId)
     ->set([
         'status' => 'completed',
         'completed_at' => date('Y-m-d H:i:s')
     ])
     ->update();
// ********

    // Recalculate progress
    $progress = $this->recalculateProgress($taskId);


    // Fetch task, project and member details
    $taskModel    = new \App\Models\TaskModel();
    $memberModel  = new \App\Models\MemberModel();
    $projectModel = new \App\Models\ProjectModel();

    $task   = $taskModel->find($taskId);
    $member = $memberModel->find($memberId);
    $project = $projectModel->find($task['project_id'] ?? null);

    $projectName = $project['name'] ?? 'Unknown Project';
    $taskTitle   = $task['title'] ?? 'Task';
    $memberName  = $member['name'] ?? 'Member';
    $projectId   = $project['id'] ?? null;

    // Build hyperlinked message: project tasks list and task edit URL
    // Project tasks listing: /projects/{projectId}/tasks
    // Task edit page: /projects/{projectId}/tasks/edit/{taskId}
    $projectLink = $projectId ? "/projects/{$projectId}/tasks" : '#';
    $taskLink = ($projectId) ? "/projects/{$projectId}/tasks/edit/{$taskId}" : '#';

    $message = "Task <a href='{$taskLink}'><b>{$taskTitle}</b></a> under project <a href='{$projectLink}'><b>{$projectName}</b></a> has been completed by <b>{$memberName}</b>";

    // Insert notification into DB
    $notificationModel = new \App\Models\NotificationModel();
    $notificationModel->insert([
        'project_id' => $task['project_id'] ?? null,
        'task_id'    => $taskId,
        'message'    => $message,
        'is_read'    => 0,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return $progress;
}







public function markMemberCompletedOnce($taskId, $memberId)
{
    // Fetch assignment
    $row = $this->where('task_id', $taskId)
                ->where('member_id', $memberId)
                ->first();

    //  Not assigned
    if (!$row) {
        return [
            'status'  => false,
            'message' => 'Task not assigned to you.'
        ];
    }

    //  ALREADY COMPLETED (BLOCK EVERYTHING)
    if ($row['status'] === 'completed') {
        return [
            'status'  => false,
            'message' => 'Already task completed!'
        ];
    }

   

    //  FIRST & ONLY COMPLETION
    $this->update($row['id'], [
        'status'       => 'completed',
        'completed_at' => date('Y-m-d H:i:s')
    ]);

    //  Update progress
    $progress = $this->recalculateProgress($taskId);

    return [
        'status'   => true,
        'message'  => 'Task marked completed successfully!',
        'progress' => $progress
    ];
}


}
