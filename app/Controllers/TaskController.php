<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\ProjectModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class TaskController extends Controller
{
    protected $taskModel;
    protected $projectModel;
    protected $tasksMembersModel;

    public function __construct()
    {
        $this->taskModel    = new TaskModel();
        $this->projectModel = new ProjectModel();
        $this->tasksMembersModel = new \App\Models\TasksMembersModel();
        
    }

public function view($projectId)
{
    $project = $this->projectModel->find($projectId);

    if (! $project) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Project not found");
    }

    // Load models
    $taskModel        = new \App\Models\TaskModel();
    $taskMemberModel  = new \App\Models\TasksMembersModel(); // ✔ Correct model name
    $memberModel      = new \App\Models\MemberModel();

    // ----------------------------------------------------------------------------------------------------
    //  FILTERS
    // ----------------------------------------------------------------------------------------------------
    $status     = $this->request->getGet('status');
    $priority   = $this->request->getGet('priority');
    $startDate  = $this->request->getGet('start_date');
    $endDate    = $this->request->getGet('end_date');

    // Base query
    $taskModel->where('project_id', $projectId);

    if (!empty($status)) {
        $taskModel->where('status', $status);
    }

    if (!empty($priority)) {
        $taskModel->where('priority', $priority);
    }

    if (!empty($startDate)) {
        $taskModel->where('due_date >=', $startDate);
    }

    if (!empty($endDate)) {
        $taskModel->where('due_date <=', $endDate);
    }

    // Fetch filtered tasks
    $tasks = $taskModel->findAll();
    // ----------------------------------------------------------------------------------------------------
    // FORMAT due_date FOR DISPLAY
foreach ($tasks as &$task) {
    if (!empty($task['due_date'])) {
        $task['due_date'] = date('Y-m-d H:i:s', strtotime($task['due_date']));
    }
}


    
    // STEP 2 — FIX ADMIN PANEL (FETCH MEMBER-WISE STATUS)
    foreach ($tasks as &$task) {

    // Get all task-member rows
    $assignedRows = $taskMemberModel
        ->where('task_id', $task['id'])
        ->findAll();

    // Prepare assigned members array
    $task['assigned_members'] = [];

    foreach ($assignedRows as $row) {

        // Get member details
        $member = $memberModel->find($row['member_id']);

        if ($member) {
            $task['assigned_members'][] = [
                'name'   => $member['name'],
                'status' => $row['status'],
            ];
        }
    }

    // ✅ Calculate progress for this task
    $task['progress'] = $taskMemberModel->getCompletionPercentage($task['id']);
}


    return view('projects/tasks/view', [
        'project'   => $project,
        'tasks'     => $tasks,

        // pass filters back to the view
        'status'     => $status,
        'priority'   => $priority,
        'startDate'  => $startDate,
        'endDate'    => $endDate
    ]);
}




    // SHOW CREATE FORM
    public function create($projectId)
    {
        $project = $this->projectModel->find($projectId);

        if (! $project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Project not found");
        }

        return view('projects/tasks/create', [
            'project' => $project
        ]);
    }

    

   // STORE NEW TASK
public function store($projectId)
{
    // Fetch project
    $project = $this->projectModel->find($projectId);

    if (! $project) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Project not found");
    }

    // Get dates
    $today     = date('Y-m-d');
    $startDate = $project['start_date'];
    $endDate   = $project['end_date'];
    $dueDate   = $this->request->getPost('due_date');

    // Min date = today or project start date (whichever is greater)
    $minDate = ($today > $startDate) ? $today : $startDate;

    // ---- VALIDATION ----
    if ($dueDate < $minDate || $dueDate > $endDate) {
        return redirect()->back()->withInput()->with('error',
            "Due date must be between $minDate and $endDate."
        );
    }

    
    // SAVE
    $this->taskModel->save([
        'project_id'  => $projectId,
        'title'       => $this->request->getPost('title'),
        'description' => $this->request->getPost('description'),
        'status'      => $this->request->getPost('status'),
        'priority'    => $this->request->getPost('priority'),
        'due_date'    => $dueDate,
    ]);

    return redirect()->to("/projects/$projectId/tasks")
         ->with('success', 'Task created successfully');
}

    

   public function edit($projectId, $taskId)
    {
    $projectModel = new \App\Models\ProjectModel();
    $taskModel = new \App\Models\TaskModel();

    // Fetch project
    $project = $projectModel->find($projectId);

    if (! $project) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Project not found");
    }

    // Fetch task
    $task = $taskModel
                ->where('project_id', $projectId)
                ->find($taskId);

    if (! $task) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Task not found");
    }

    // Send to view
    return view('projects/tasks/edit', [
        'project' => $project,
        'task'    => $task
    ]);
     }


    // UPDATE TASK
public function update($projectId, $taskId)
{
    // Fetch project
    $project = $this->projectModel->find($projectId);

    if (! $project) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Project not found");
    }

    $today     = date('Y-m-d');
    $startDate = $project['start_date'];
    $endDate   = $project['end_date'];
    $dueDate   = $this->request->getPost('due_date');

    // Min date = today or project start date
    $minDate = ($today > $startDate) ? $today : $startDate;

    // ---- VALIDATION ----
    if ($dueDate < $minDate || $dueDate > $endDate) {
        return redirect()->back()->withInput()->with('error',
            "Due date must be between $minDate and $endDate."
        );
    }

    // UPDATE
    $this->taskModel->update($taskId, [
        'title'       => $this->request->getPost('title'),
        'description' => $this->request->getPost('description'),
        'status'      => $this->request->getPost('status'),
        'priority'    => $this->request->getPost('priority'),
        'due_date'    => $dueDate
    ]);

     



    

    return redirect()->to("/projects/$projectId/tasks")
                     ->with('success', 'Task updated successfully');
}


    public function delete($projectId, $taskId)
    {
         $this->taskModel->delete($taskId);

    return redirect()->to("/projects/$projectId/tasks")
                     ->with('success', 'Task deleted successfully');
    }







    public function assign($projectId, $taskId)
{
    $project = $this->projectModel->find($projectId);
    $task = $this->taskModel->find($taskId);

    if (! $project || ! $task) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $memberModel = new \App\Models\MemberModel();
    $members = $memberModel->findAll();

    $tasksMembersModel = new \App\Models\TasksMembersModel();
    
    // already assigned members
    $assigned = $tasksMembersModel->where('task_id', $taskId)->findAll();
    $assignedIds = array_column($assigned, 'member_id');

    return view('projects/tasks/assign', [
        'project'     => $project,
        'task'        => $task,
        'members'     => $members,
        'assignedIds' => $assignedIds
    ]);
}

    // ============ YOUR ASSIGNSAVE GOES HERE ============
public function assignSave($projectId, $taskId)
{
    $tasksMembersModel = new \App\Models\TasksMembersModel();
    $taskModel         = new \App\Models\TaskModel();

    // 1 Get existing assigned members
    $existing = $tasksMembersModel->where('task_id', $taskId)->findAll();
    $existingIds = array_column($existing, 'member_id');

    // 2 Get submitted members from form
    $members = $this->request->getPost('members') ?? [];

    // 3 Add new members only (do not delete old ones)
    foreach ($members as $m) {
        if (!in_array($m, $existingIds)) {
            $tasksMembersModel->insert([
                'task_id'   => $taskId,
                'member_id' => $m,
                'status'    => 'pending' // new member starts as pending
            ]);
        }
    }

    // 4 Optionally remove members who were unassigned in the form
    $toRemove = array_diff($existingIds, $members);
    if (!empty($toRemove)) {
        $tasksMembersModel->where('task_id', $taskId)
                          ->whereIn('member_id', $toRemove)
                          ->delete();
    }

    // 5 Recalculate progress after changes
    $percentage = $tasksMembersModel->recalculateProgress($taskId);

    // 6 Update task progress
    $taskModel->update($taskId, [
        'progress' => $percentage
    ]);

    return redirect()->to("/projects/$projectId/tasks")
                     ->with('success', 'Members assigned successfully');
}





// ==========================================
// MARK TASK AS COMPLETED (ADMIN NOTIFICATION)
// ==========================================
public function markComplete($projectId, $taskId, $memberId = null)
{
    $task = $this->taskModel->find($taskId);

    if (!$task) {
        return redirect()->back()->with('error', 'Task not found.');
    }

    // 1️⃣ Update task-member status if memberId provided
    if ($memberId) {
        $tasksMembersModel = new \App\Models\TasksMembersModel();
        $memberRow = $tasksMembersModel
                        ->where('task_id', $taskId)
                        ->where('member_id', $memberId)
                        ->first();

        if ($memberRow) {
            $tasksMembersModel->update($memberRow['id'], [
                'status' => 'completed'
            ]);

            // Recalculate task progress
            $progress = $tasksMembersModel->recalculateProgress($taskId);
        }
    }

    // 2️⃣ Update overall task status if progress = 100%
    $taskProgress = $this->tasksMembersModel->getCompletionPercentage($taskId);

    if ($taskProgress == 100) {
        $this->taskModel->update($taskId, [
            'status' => 'completed',
            'progress' => 100
        ]);
    }

    // 3️⃣ Insert notification for admin
    $notificationModel = new \App\Models\NotificationModel();

   $notificationModel->insert([
    'project_id' => $task['project_id'],
    'task_id'    => $taskId,
    'message'    => "Task <b>{$task['title']}</b> under project <b>{$this->projectModel->find($task['project_id'])['name']}</b> has been completed" 
                    . ($memberId ? " by <b>" . (new \App\Models\MemberModel())->find($memberId)['name'] . "</b>" : ""),
    'is_read'    => 0,
    'created_at' => date('Y-m-d H:i:s'),
]);


    return redirect()->back()->with('success', 'Task marked as completed!');
}



}