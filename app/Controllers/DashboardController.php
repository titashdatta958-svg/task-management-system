<?php namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $notificationModel = new NotificationModel();

        // All notifications (latest first)
        $notifications = $notificationModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Count of unread notifications
        $unreadCount = $notificationModel
            ->where('is_read', 0)
            ->countAllResults();

        return view('dashboard/index', [
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount
        ]);
    }
}
