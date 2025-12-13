<?= view('layout/header') ?>

<style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #ff8800ff;
        padding: 40px;
    }

    .container1 {
        max-width: 1000px;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    label {
        font-weight: 600;
        margin-top: 10px;
        display: block;
    }

    input, textarea, select {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        margin-top: 6px;
    }

    .btn-submit {
        background: #28a745;
        color: #fff;
        padding: 10px 14px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        margin-top: 15px;
        font-size: 15px;
    }

    .back-link {
        display: inline-block;
        margin-top: 15px;
        background: #007bff;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
    }
</style>

<div class="container1">

    <h1>Create Task for Project: <?= esc($project['name']) ?></h1>

    <?php
$today  = date('Y-m-d');
$start  = $project['start_date'];
$end    = $project['end_date'];

$minDate = ($today > $start) ? $today : $start;
$maxDate = $end;
?>
    <form action="/projects/<?= $project['id'] ?>/tasks/store" method="post">
        <?= csrf_field() ?>
        <!--  Display error message if any -->
<?php if(session()->getFlashdata('error')): ?>
    <div style="background:#ffdddd; padding:10px; margin:10px 0; color:#d00; border-left:4px solid red;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="in-progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>

        <label>Priority</label>
        <select name="priority">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>

        <label>Due Date</label>
       <input type="datetime-local" 
       name="due_date" 
       min="<?= $minDate . 'T00:00' ?>" 
       max="<?= $maxDate . 'T23:59' ?>">


        <button class="btn-submit">Create Task</button>
        
    </form>

    <!-- Correct Back Link -->
    <a class="back-link" href="/projects/<?= $project['id'] ?>/tasks">← Back to Tasks</a>
</div>

<?= view('layout/footer') ?>
