<?= view('layout/header')?>



<style>
body {
    font-family: "Poppins", sans-serif;
    background-color: #ff8800;
    padding: 40px;
}

.on:hover {
    background: #afe8ffff;

}

button {
    border-radius: 6px;
    background: #4b84ffff;
    border: none;
    color: #fff;
}
</style>

<div style="max-width:1000px;margin:30px auto;">
    <h1>Your Assigned Tasks</h1>

    <?php if (session()->getFlashdata('success')): ?>
    <div style="color:green"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div style="color:red"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table style="width:100%;border-collapse:collapse;background:#fff;box-shadow:0 3px 6px rgba(0,0,0,0.06);">
        <thead style="background:#000;color:#fff; ">
            <tr>
                <th style="padding:10px">Project</th>
                <th style="padding:10px">Title</th>
                <th style="padding:10px">Priority</th>
                <th style="padding:10px">Status</th>
                <th style="padding:10px">Due Date</th>
                <th style="padding:10px">Completed At</th>

                <th style="padding:10px">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tasks)): ?>
            <tr>
                <td colspan="6" style="padding:12px">No tasks assigned.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($tasks as $t): ?>
            <tr class="on">
                <td style="padding:10px"><?= esc($t['project_name']) ?></td>
                <td style="padding:10px"><?= esc($t['title'] ?? $t['name'] ?? '—') ?></td>
                <td style="padding:10px"><?= esc($t['priority']) ?></td>
                <td style="padding:10px"><?= esc($t['status']) ?></td>
                <td style="padding:10px"><?= esc($t['due_date'] ?: '—') ?></td>
                <td style="padding:10px">
                <?= $t['completed_at'] ? date('Y-m-d H:i:s', strtotime($t['completed_at'])) : '—' ?>
                </td>

                <td style="padding:10px">
                    <a href="/member/task/update/<?= esc($t['id']) ?>"> <button style="color:#fff;">Update
                            Status</button></a>
                    
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
</div>

<?= view('layout/footer') ?>