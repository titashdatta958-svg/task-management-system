<?= view('layout/header') ?>

<div class="container mt-4" style="max-width: 600px;">
    <h3>Change Password</h3>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach(session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="/change-password" method="post">
        <?= csrf_field() ?>

        <!-- OLD PASSWORD -->
        <div class="mb-3">
            <label><b>Old Password</b></label>
            <input type="password" name="old_password" class="form-control" required>
        </div>

        <!-- NEW PASSWORD -->
        <div class="mb-3">
            <label><b>New Password</b></label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="mb-3">
            <label><b>Confirm Password</b></label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Update Password</button>
    </form>
</div>

<?= view('layout/footer') ?>
