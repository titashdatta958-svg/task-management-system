<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartExpense</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="/" style="font-weight: bold;">Tasks-Management-System</a>
            <div class="d-flex">
                <?php if(session()->get('logged_in')): ?>
                <span class="me-3"><?= esc(session()->get('user_name')) ?></span>
                <a class="btn btn-outline-secondary btn-sm" href="/logout"
                    style='font-weight: bold; background-color:blue' class="new1">Logout</a>

                <?php if(session()->get('logged_in')): ?>
                <a href="/change-password" class="btn btn-warning btn-sm" class="new1">Change Password</a>
                <?php endif; ?>

                <?php else: ?>
                <a class="btn btn-outline-primary btn-sm me-2" href="/login">Login</a>
                <a class="btn btn-primary btn-sm" href="/register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container">