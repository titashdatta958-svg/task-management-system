<?= view('layout/header') ?>


<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Login</h3>
    
    
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; echo "<body style='background-color:aqua'>";?>

    <form action="/login" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?= set_value('email') ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary" type="submit">Login</button> 
    </form>

   <div style=" margin-top:15px;">
    <a href="<?= base_url('reset-request'); ?>" class="btn btn-primary" >
        Forgot Password? 
    </a>
</div>
<br>
   <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>
</div>
</div>



<?= view('layout/footer') ?>


<!-- <input type="password" name="password" class="form-control">
<input type="password" name="confirm_password" class="form-control"> -->




