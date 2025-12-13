<?= view('layout/header') ?>

<style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #ff8800ff;
        padding: 40px;
    }

   .form-box {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: right 100px;
    }


    h2 {
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .member-item {
        background: #fafafa;
        border: 1px solid #ddd;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .member-item label {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .member-item input[type=checkbox] {
        transform: scale(1.3);
        cursor: pointer;
    }

    .btn-submit {
        background: #007bff;
        color: #fff;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        width: 100%;
        font-size: 15px;
        transition: .3s;
        margin-top: 15px;
    }

    .btn-submit:hover {
        opacity: .85;
        transform: translateY(-2px);
    }
</style>

<div class="form-box">
    <h2>Assign Members to: <?= esc($task['title']) ?></h2>

    <form action="/projects/<?= $project['id'] ?>/tasks/assignSave/<?= $task['id'] ?>" method="post">

        <?php foreach ($members as $m): ?>
            <div class="member-item">
                <label>
                    <input type="checkbox" 
                           name="members[]" 
                           value="<?= $m['id'] ?>"
                           <?= in_array($m['id'], $assignedIds) ? 'checked' : '' ?>>

                    <?= esc($m['name']) ?> (<?= esc($m['email']) ?>)
                </label>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn-submit">Save Assignment</button>
    </form>
</div>

<?= view('layout/footer') ?>
