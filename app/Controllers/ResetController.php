<?php

namespace App\Controllers;
use App\Models\ResetRequestModel;
use App\Models\MemberModel;  

class ResetController extends BaseController
{
    public function showRequestForm()
    {
        return view('reset/request_form');
    }

    public function submitRequest()
    {
        $email = $this->request->getPost('email');

        // Load Member Model
        $memberModel = new MemberModel();

        // Check if email exists
        $member = $memberModel->where('email', $email)->first();

        if (!$member) {
            return redirect()->back()->with('error', 'Invalid email address! No member found.');
        }

        $model = new ResetRequestModel();
        $model->save([
            'email'      => $email,
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')->with('success', 'Reset request sent successfully!');
    }
}

