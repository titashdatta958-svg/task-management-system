<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// -----------------------
// AUTH ROUTES
// -----------------------
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::registerPost');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard (protected)
    $routes->get('dashboard', 'DashboardController::index');


    // -----------------------
    // PROJECT ROUTES (protected)
    // -----------------------
    $routes->get('projects', 'ProjectController::index');
    $routes->get('projects/create', 'ProjectController::create');
    $routes->post('projects/store', 'ProjectController::store');
    $routes->get('projects/edit/(:num)', 'ProjectController::edit/$1');
    $routes->post('projects/update/(:num)', 'ProjectController::update/$1');
    $routes->get('projects/delete/(:num)', 'ProjectController::delete/$1');

    // -----------------------
    // TASK ROUTES (protected)
    // -----------------------
    $routes->group('projects', function($routes) {

        $routes->get('(:num)/tasks', 'TaskController::view/$1');
        $routes->get('(:num)/tasks/create', 'TaskController::create/$1');
        $routes->post('(:num)/tasks/store', 'TaskController::store/$1');

        // Edit, Update, Delete routes for tasks
        $routes->get('(:num)/tasks/edit/(:num)', 'TaskController::edit/$1/$2');
        $routes->post('(:num)/tasks/update/(:num)', 'TaskController::update/$1/$2');
        $routes->get('(:num)/tasks/delete/(:num)', 'TaskController::delete/$1/$2');

    });

// member routes
    $routes->group('members', function($routes) {
    $routes->get('/', 'MemberController::index');
    $routes->get('create', 'MemberController::create');
    $routes->post('store', 'MemberController::store');
    $routes->get('edit/(:num)', 'MemberController::edit/$1');
    $routes->post('update/(:num)', 'MemberController::update/$1');
    $routes->get('delete/(:num)', 'MemberController::delete/$1');
});

    // -----------------------
    // TASK ASSIGNMENT ROUTES (protected)
    // -----------------------
    $routes->get('projects/(:num)/tasks/assign/(:num)', 'TaskController::assign/$1/$2');
    $routes->post('projects/(:num)/tasks/assignSave/(:num)', 'TaskController::assignSave/$1/$2');

    // Member Dashboard
    $routes->get('/member/dashboard', 'MemberDashboard::index');

    // Member Task Update
    $routes->get('/member/task/update/(:num)', 'MemberTaskController::edit/$1');
    $routes->post('/member/task/update/(:num)', 'MemberTaskController::update/$1');

});

// Change password (forced or normal)
$routes->get('/change-password', 'AuthController::changePassword');
$routes->post('/change-password', 'AuthController::changePasswordPost');

// MEMBER: forgot password request
$routes->get('member/forgot-password', 'ResetController::showRequestForm');
$routes->post('member/forgot-password', 'ResetController::submitRequest');

// ADMIN: view requests
$routes->get('admin/reset-requests', 'AdminResetController::list');

// ADMIN: approve / reject
$routes->get('admin/reset-requests/approve/(:num)', 'AdminResetController::approve/$1');
$routes->get('admin/reset-requests/reject/(:num)', 'AdminResetController::reject/$1');

// ADMIN: show reset form
$routes->get('admin/reset-requests/(:num)', 'AdminResetController::showResetForm/$1');
$routes->post('admin/reset-requests/(:num)/reset', 'AdminResetController::doReset/$1');

// ADMIN: mark notification as read
$routes->post('admin/notifications/(:num)/mark-read', 'AdminResetController::markNotificationRead/$1');

// ADMIN: direct reset from members list
$routes->get('admin/members/reset/(:num)', 'AdminResetController::resetMemberForm/$1');
$routes->post('admin/members/reset/(:num)', 'AdminResetController::resetMemberPost/$1');


// MEMBER: forgot password request
$routes->get('member/forgot-password', 'ResetController::showRequestForm');
$routes->post('member/forgot-password', 'ResetController::submitRequest');

// ADMIN: view requests
$routes->get('admin/reset-requests', 'AdminResetController::list');

// ADMIN: approve / reject (optional quick actions)
$routes->get('admin/reset-requests/approve/(:num)', 'AdminResetController::approve/$1');
$routes->get('admin/reset-requests/reject/(:num)', 'AdminResetController::reject/$1');

// ADMIN: show reset form (for a request)
$routes->get('admin/reset-requests/(:num)', 'AdminResetController::showResetForm/$1');
$routes->post('admin/reset-requests/(:num)/reset', 'AdminResetController::doReset/$1');

// ADMIN: mark notification as read
$routes->post('admin/notifications/(:num)/mark-read', 'AdminResetController::markNotificationRead/$1');

// ADMIN: direct reset from members list
$routes->get('admin/members/reset/(:num)', 'AdminResetController::resetMemberForm/$1');
$routes->post('admin/members/reset/(:num)', 'AdminResetController::resetMemberPost/$1');


$routes->get('/member/dashboard', 'MemberController::dashboard');





// Analytica
$routes->get('/analytics', 'AnalyticsController::index');




