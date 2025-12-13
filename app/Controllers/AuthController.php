<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MemberModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $helpers = ['url', 'form', 'session'];

    // -------------------------
    // Register
    // -------------------------
    public function register()
    {
        return view('auth/register');
    }

    public function registerPost()
    {
        $registerRules = [
            'name'              => 'required|min_length[3]|max_length[50]',
            'email'             => 'required|valid_email',
            'password'          => 'required|min_length[6]|max_length[50]',
            'confirm_password'  => 'required|matches[password]',
        ];

        if (!$this->validate($registerRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        if ($userModel->where('email', $data['email'])->first()) {
            return redirect()->back()->with('error', 'Email already registered.');
        }

        $userModel->save($data);

        return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
    }

    // -------------------------
    // Login
    // -------------------------
    public function login()
    {
        return view('auth/login');
    }

    public function loginPost()
    {
        $loginRules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($loginRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }

        // Check if this email also exists in members table
        $memberModel = new MemberModel();
        $member = $memberModel->where('email', $email)->first();

        if ($member) {
            // MEMBER LOGIN
            session()->set([
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'user_email' => $user['email'],
                'member_id'  => $member['id'],
                'role'       => 'member',
                'logged_in'  => true,
            ]);

            return redirect()->to('/member/dashboard')->with('success', 'Logged in as Member');
        }

        // ADMIN LOGIN
        session()->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'role'       => 'admin',
            'logged_in'  => true,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Successfully Logged-in!');
    }

    // -------------------------
    // Logout
    // -------------------------
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully.');
    }

    // -------------------------
    // Change Password (VIEW)
    // -------------------------
    public function changePassword()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        return view('auth/change_password'); // SAME VIEW for ADMIN + MEMBER
    }

    // -------------------------
    // Change Password (POST)
    // -------------------------
    public function changePasswordPost()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $rules = [
            'old_password'     => 'required|min_length[6]',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role   = session()->get('role');   // admin/member
        $userId = session()->get('user_id');

        if ($role === 'admin') {
            $model = new UserModel();
        } else {
            $model = new MemberModel();
        }

        $user = $model->find($userId);

        if (! password_verify($this->request->getPost('old_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Old password is incorrect!');
        }

        // Update password
        $model->update($userId, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to(
            $role === 'admin' ? '/dashboard' : '/member/dashboard'
        )->with('success', 'Password changed successfully!');
    }
}
