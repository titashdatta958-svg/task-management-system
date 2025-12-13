<?php namespace App\Controllers;

use App\Models\ResetRequestModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class AdminResetController extends Controller
{
    protected $helpers = ['url','form','session'];

    // list all requests
    public function list()
    {
        $model = new ResetRequestModel();
        $requests = $model->orderBy('created_at','DESC')->findAll();

        // fetch only unread notifications
        $notificationModel = new NotificationModel();
        $notifications = $notificationModel
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/reset_request_list', [
            'requests' => $requests,
            'notifications' => $notifications,
        ]);
    }

    // optionally change status to approved (if you want a separate step)
    public function approve($id)
    {
        $model = new ResetRequestModel();
        $req = $model->find($id);
        if (! $req) return redirect()->back()->with('error','Request not found');

        $model->update($id, ['status' => 'approved']);
        return redirect()->to("/admin/reset-requests/{$id}")->with('success','Approved. Set password now.');
    }

    public function reject($id)
    {
        $model = new ResetRequestModel();
        $req = $model->find($id);
        if (! $req) {
            return redirect()->back()->with('error','Reset request not found.');
        }

        $model->delete($id);

        return redirect()
            ->back()
            ->with('success','Request rejected and permanently removed.');
    }

    public function showResetForm($id)
    {
        $model = new ResetRequestModel();
        $request = $model->find($id);
        if (! $request) return redirect()->back()->with('error','Request not found');

        return view('admin/reset_password_form', ['request' => $request]);
    }

    public function doReset($id)
    {
        $rules = [
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $resetModel = new ResetRequestModel();
        $request = $resetModel->find($id);
        if (! $request) return redirect()->back()->with('error','Request not found');

        // update user password
        $userModel = new UserModel();
        $userModel->update($request['user_id'], [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);

        // mark request completed
        $resetModel->update($id, ['status' => 'completed']);

        return redirect()->to('/admin/reset-requests')->with('success', 'Password reset and request completed.');
    }

    
    public function resetMemberForm($memberId)
    {
        $userModel = new UserModel();
        $member = $userModel->find($memberId);
        if (! $member) return redirect()->back()->with('error','Member not found');

        return view('admin/reset_member_direct', ['member' => $member]);
    }

    public function resetMemberPost($memberId)
    {
        $rules = [
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->update($memberId, ['password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)]);

        return redirect()->to('/members')->with('success','Password updated for member.');
    }

    public function markNotificationRead($notificationId)
    {
        $notificationModel = new NotificationModel();
        $notification = $notificationModel->find($notificationId);
        
        if (!$notification) {
            return $this->response->setJSON(['success' => false, 'message' => 'Notification not found']);
        }

        // Delete notification permanently
        $notificationModel->delete($notificationId);

        return $this->response->setJSON(['success' => true, 'message' => 'Notification removed']);
    }

}
