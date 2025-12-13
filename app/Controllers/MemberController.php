<?php
namespace App\Controllers;

use App\Models\MemberModel;
use CodeIgniter\Controller;

use App\Models\NotificationModel;
use App\Models\TasksMembersModel;
use App\Models\TaskModel;


class MemberController extends Controller
{
    protected $memberModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
    }

    // List members
    public function index()
    {
        // Get filter inputs
        $filterName  = $this->request->getGet('name');
        $filterEmail = $this->request->getGet('email');

        // Load model
        $memberModel = new \App\Models\MemberModel();

        // Base query
        $builder = $memberModel;

        // Apply filters
        if (!empty($filterName)) {
            $builder->like('name', $filterName);
        }

        if (!empty($filterEmail)) {
            $builder->like('email', $filterEmail);
        }

        // Paginate
        $perPage = 3;
        $members = $memberModel->paginate($perPage);
        $pager   = $memberModel->pager;

        $currentPage = $pager->getCurrentPage();
        $start = ($currentPage - 1) * $perPage;

        return view('members/index', [
            'members'      => $members,
            'pager'        => $pager,
            'filterName'   => $filterName,
            'filterEmail'  => $filterEmail,
            'start'        => $start
        ]);
    }

    // Create form
    public function create()
    {
        return view('members/create');
    }

    // Store new member
    public function store()
    {
        $this->memberModel->save([
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ]);

        return redirect()->to('/members')->with('success', 'Member added successfully');
    }

    // Edit form
    public function edit($id)
    {
        $member = $this->memberModel->find($id);

        return view('members/edit', ['member' => $member]);
    }

    // Update member
    public function update($id)
    {
        $this->memberModel->update($id, [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ]);

        return redirect()->to('/members')->with('success', 'Member updated successfully');
    }

    // Delete member
    public function delete($id)
    {
        $this->memberModel->delete($id);

        return redirect()->to('/members')->with('success', 'Member deleted successfully');
    }

    // MEMBER PASSWORD RESET
    public function requestReset($memberId)
    {
        $memberModel = new \App\Models\MemberModel();
        $member = $memberModel->find($memberId);

        return view('members/reset_request_form', ['member' => $member]);
    }

    public function requestResetPost($memberId)
    {
        $memberModel = new \App\Models\MemberModel();
        $member = $memberModel->find($memberId);

        $reqModel = new \App\Models\PasswordResetRequestModel();

        $reqModel->insert([
            'member_id'  => $memberId,
            'email'      => $member['email'],
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/members')
            ->with('success', 'Password reset request submitted. Admin will approve.');
    }


    // --------------------------------------------
    //  ADD THIS FUNCTION HERE (STATUS UPDATE)
    // --------------------------------------------
public function updateStatus($taskId)
{
    $status = $this->request->getPost('status');

    $tasksMembersModel = new \App\Models\TasksMembersModel(); 
    $taskModel         = new \App\Models\TaskModel();
    $memberModel       = new \App\Models\MemberModel();
    $notificationModel = new \App\Models\NotificationModel();
    $projectModel      = new \App\Models\ProjectModel();

    // Logged-in member ID
    $memberId = session()->get('member_id');

    // 1 UPDATE MEMBER STATUS
    $tasksMembersModel->where('task_id', $taskId)
                      ->where('member_id', $memberId)
                      ->set(['status' => $status])
                      ->update();

    // 2 RECALCULATE PROGRESS (ONLY THIS LINE)
    $percentage = $tasksMembersModel->recalculateProgress($taskId);

    // 3 UPDATE TASK PROGRESS
    $taskModel->update($taskId, [
        'progress' => $percentage
    ]);

    // 4 SEND NOTIFICATION IF MEMBER COMPLETED TASK
    if ($status === 'completed') {

        $task    = $taskModel->find($taskId);
        $project = $projectModel->find($task['project_id']);
        $member  = $memberModel->find($memberId);

        $message = "{$member['name']} has completed the task '{$task['title']}' in project '{$project['project_title']}'";

        $notificationModel->insert([
            'message'    => $message,
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    return redirect()->back()->with('success', 'Status updated successfully');
}




} // <-- END OF CONTROLLER
