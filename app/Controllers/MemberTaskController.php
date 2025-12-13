<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TaskModel;
use App\Models\TasksMembersModel;

class MemberTaskController extends Controller
{
    protected $taskModel;
    protected $tm;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
        $this->tm = new TasksMembersModel();
    }

public function edit($taskId)
{
    if (! session()->get('logged_in') || session()->get('role') !== 'member') {
        return redirect()->to('/login');
    }

    $memberId = session()->get('member_id');

    // Ensure task belongs to this member
    $assigned = $this->tm->where('task_id', $taskId)
                         ->where('member_id', $memberId)
                         ->first();

    if (! $assigned) {
        return redirect()->to('/member/dashboard')
                         ->with('error', 'You are not assigned to this task.');
    }

    $task = $this->taskModel->find($taskId);
    if (! $task) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Task not found');
    }

    // Member-specific status from task_members
    $memberStatus = isset($assigned['status']) ? $assigned['status'] : 'pending';

    // pass both task and memberStatus to view
    return view('member/update_task', [
        'task' => $task,
        'memberStatus' => $memberStatus
    ]);
}




  public function update($taskId)
{
    if (!session()->get('logged_in') || session()->get('role') !== 'member') {
        return redirect()->to('/login');
    }

    $memberId  = session()->get('member_id');
    $newStatus = $this->request->getPost('status');

    // Fetch assignment
    $assigned = $this->tm->where('task_id', $taskId)
                         ->where('member_id', $memberId)
                         ->first();

    if (!$assigned) {
        return redirect()->to('/member/dashboard')
            ->with('error', 'You are not assigned to this task.');
    }

    $currentStatus = $assigned['status'];

    //  COMPLETED → NOTHING ALLOWED
    if ($currentStatus === 'completed') {
        return redirect()->back()
            ->with('errors', ['Already task completed!']);
    }

    //  IN-PROGRESS → CANNOT GO BACK TO PENDING
    if ($currentStatus === 'in-progress' && $newStatus === 'pending') {
        return redirect()->back()
            ->with('errors', ['You cannot move back from In Progress!']);
    }

    //  FIRST TIME COMPLETION
    if ($newStatus === 'completed') {

        $this->tm->update($assigned['id'], [
            'status'       => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ]);

        $this->tm->recalculateProgress($taskId);

        return redirect()->to('/member/dashboard')
           ->with('success', 'Task marked completed successfully!');
    }

    //  ALLOWED UPDATE (pending → in-progress)
    $this->tm->update($assigned['id'], [
        'status'       => $newStatus,
        'completed_at' => null
    ]);

    return redirect()->to('/member/dashboard')
        ->with('success', 'Task status updated.');
}


}