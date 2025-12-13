<?= view('layout/header') ?>
<style>
        body {
        font-family: "Poppins", sans-serif;
        background-color: #ff8800;
        padding: 40px;
    }
</style>

<div style="max-width:600px;margin:40px auto;">
    <h2>Update Task Status</h2>

    <?php if(session()->getFlashdata('errors')): ?>
        <div style="background:#ffdddd;padding:10px;border-left:4px solid #cc0000;border-radius:6px;">
            <?php foreach(session()->getFlashdata('errors') as $err): ?>
                <div><?= esc($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>



    <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 3px 10px rgba(0,0,0,0.06);">
        <form method="post" action="/member/task/update/<?= esc($task['id']) ?>">
            <?= csrf_field() ?>

            <p><strong>Task:</strong> <?= esc($task['title'] ?? $task['name'] ?? '—') ?></p>
            <p><strong>Due:</strong> <?= esc($task['due_date'] ?: '—') ?></p>


 
            
           <label>Status</label>
<select name="status" required
    style="width:100%;padding:8px;margin-top:6px;border-radius:6px;border:1px solid #ccc;">

    <!-- PENDING -->
    <option value="pending"
        <?= ($memberStatus === 'pending') ? 'selected' : '' ?>
        <?= in_array($memberStatus, ['in-progress','completed']) ? 'disabled' : '' ?>>
        Pending
    </option>

    <!-- IN PROGRESS -->
    <option value="in-progress"
        <?= ($memberStatus === 'in-progress') ? 'selected' : '' ?>
        <?= ($memberStatus === 'completed') ? 'disabled' : '' ?>>
        In Progress
    </option>

    <!-- COMPLETED -->
    <option value="completed"
        <?= ($memberStatus === 'completed') ? 'selected disabled' : '' ?>>
        Completed
    </option>
</select>




            <button type="submit" style="margin-top:12px;background:#28a745;color:#fff;border:none;padding:10px 14px;border-radius:6px;">Save</button>
        </form>
    </div>

    <p style="margin-top:10px;"><a href="/member/dashboard"> <button class=add-btn >← Back to Dashboard</button></a></p>
</div>


<style>
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
    border: none;
}

.add-btn:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}
</style>

<?= view('layout/footer') ?>
