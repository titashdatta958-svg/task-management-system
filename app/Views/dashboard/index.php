<?= view('layout/header') ?>


<?php
$notificationModel = new \App\Models\NotificationModel();
$notifications = $notificationModel->where('is_read', 0)->findAll();
$unreadCount = count($notifications);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>

    <style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #ff8800ff;
        padding: 40px;
    }

    .add-btn {
        display: inline-block;
        background-color: #007bff;
        color: white;
        font-weight: 500;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
        margin-bottom: 15px;
    }

    .add-btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .add-btn i {
        margin-right: 6px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    thead {
        background-color: #000000ff;
        color: white;
    }

    th,
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    /* Action Buttons */
    .btn {
        padding: 6px 12px;
        font-size: 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        color: white;
        margin-right: 5px;
        transition: 0.3s;
    }

    .btn-edit {
        background-color: #28a745;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-status-active {
        background-color: #17a2b8;
    }

    .btn-status-deactive {
        background-color: #5fbfffff;
    }

    .btn:hover {
        opacity: 0.85;
        transform: translateY(-2px);
    }
.card4 {
    margin-left: 85px;
}


    </style>
</head>

<body>

    <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible" role="alert" style="margin: 15px 0; border-radius:8px;">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
            style="float:right; background:transparent; border:none; font-size:1.1rem;">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Category Table 1 -->

    <!-- Categories -->
    <br>
    <div class="container my-4">
        <div class="row justify-content-center g-4">
            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                <div class="card" style="width: 20rem;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Project Management</h5>
                        <a href="/projects" class="btn btn-primary">View Project</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                <div class="card" style="width: 20rem;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Members</h5>
                        <a href="/members" class="btn btn-primary">View Members</a>
                    </div>
                </div>
            </div>

           
            
   <!-- Notifications Card -->
<div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
    <div class="card" style="width: 20rem;">
        <div class="card-body text-center">
            <h5 class="card-title">Notifications</h5>

            <div class="position-relative d-inline-block">
                <a href="/admin/reset-requests" class="btn btn-primary">View Notifications</a>

                <?php if ($unreadCount > 0): ?>
                    <span class="badge bg-danger position-absolute" style="top:-8px; right:-8px;">
                        <?= $unreadCount ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>





     <div  class="card4">
                <div class="card" style="width: 20rem;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Analytics</h5>
                       <a href="/analytics" class="btn btn-primary">View Analytics</a>
                    </div>
                </div>
            </div>


</body>

</html>


<?= view('layout/footer') ?>