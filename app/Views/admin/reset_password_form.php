<?= view('layout/header') ?>

<div class="container mt-4" style="max-width:600px;">
    <h3>Reset password for <?= esc($request['email']) ?></h3>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach(session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/admin/reset-requests/<?= $request['id'] ?>/reset" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button class="btn btn-success">Reset Password</button>
    </form>
</div>

<?= view('layout/footer') ?>
