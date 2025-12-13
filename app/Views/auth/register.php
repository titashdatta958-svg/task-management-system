<?= view('layout/header') ?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Register</h3>
    <?php if(session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach(session()->getFlashdata('errors') as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>


    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    
    <form action="/register" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" value="<?= set_value('name') ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?= set_value('email') ?>" class="form-control" required>
      </div>


      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>


      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button class="btn btn-primary" type="submit">Register</button>
    </form>
  </div>

</div>

<?= view('layout/footer') ?>
