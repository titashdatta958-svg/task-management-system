<?= view('layout/header') ?>

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

table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

thead {
    background-color: #000;
    color: white;
}

th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
}

.task-row:hover {
    background-color: #b5fff5ff;
}

.task-table {
    border-radius: 10px;
    overflow: hidden;
}

.status-btn,
.priority-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    color: white;
    font-weight: 500;
}

.status-pending { background-color:#ffc107; }
.status-in-progress { background-color:#17a2b8; }
.status-completed { background-color:#28a745; }

.priority-low { background-color:#6c757d; }
.priority-medium { background-color:#007bff; }
.priority-high { background-color:#dc3545; }
</style>

<h1><?= esc($project['name']) ?></h1>
<p><?= nl2br(esc($project['description'])) ?></p>

<p>
    Start: <?= esc($project['start_date'] ?: '—') ?> |
    End: <?= esc($project['end_date'] ?: '—') ?>
</p>

<p>
    <a href="/dashboard/">Dashboard</a> |
    <a href="/projects">Back to Projects</a> |
    <a href="/projects/edit/<?= $project['id'] ?>">Edit Project</a>
</p>

<h2>Tasks For This Project</h2>

<style>
.filter-box {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
.filter-title {
    font-weight: 700;
    margin-bottom: 10px;
}
</style>

<div class="filter-box">
    <div class="filter-title">Filter Tasks</div>

    <form method="get">
        <div style="display:flex; gap:20px; flex-wrap:wrap;">
            <div>
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="Pending"     <?= ($status=='Pending') ? 'selected':'' ?>>Pending</option>
                    <option value="In Progress" <?= ($status=='In Progress') ? 'selected':'' ?>>In Progress</option>
                    <option value="Completed"   <?= ($status=='Completed') ? 'selected':'' ?>>Completed</option>
                </select>
            </div>

            <div>
                <label>Priority</label>
                <select name="priority">
                    <option value="">All</option>
                    <option value="Low"     <?= ($priority=='Low') ? 'selected':'' ?>>Low</option>
                    <option value="Medium"  <?= ($priority=='Medium') ? 'selected':'' ?>>Medium</option>
                    <option value="High"    <?= ($priority=='High') ? 'selected':'' ?>>High</option>
                </select>
            </div>

            <div>
                <label>Start Due Date</label>
                <input type="date" name="start_date" value="<?= $startDate ?>">
            </div>

            <div>
                <label>End Due Date</label>
                <input type="date" name="end_date" value="<?= $endDate ?>">
            </div>
        </div>

        <br>

        <button style="background:#007bff;color:white;padding:8px 14px;border:none;border-radius:6px;">
            Apply Filter
        </button>

        <a href="/projects/<?= $project['id'] ?>/tasks" 
           style="margin-left:10px; color:red; font-weight:bold;">Reset</a>
    </form>
</div>

<a href="/projects/<?= $project['id'] ?>/tasks/create" class="add-btn">+ Create Task</a>

<table class="task-table">
    <thead>
        <tr>
            <?php $i = 1; ?>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Priority</th>
            <th>Due Date</th>
            <th>Actions</th>
            <th>Assign Members</th>
            <th>Assigned Member Name</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($tasks)): ?>
        <?php foreach ($tasks as $t): ?>
        <tr class="task-row">

            <td><?= $i++ ?></td>
            <td><?= esc($t['title']) ?></td>
            <td><?= esc($t['description']) ?></td>

            <!-- ⭐ NEW MEMBER-WISE STATUS ⭐ -->
           <td>
<?php if (!empty($t['assigned_members'])): ?>
    <?php foreach ($t['assigned_members'] as $am): ?>
        <div style="margin-bottom:5px;">
            <strong><?= esc($am['name']) ?>:</strong>
            <span class="status-btn status-<?= strtolower(str_replace(' ', '-', $am['status'])) ?>">
                <?= esc($am['status']) ?>
            </span>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <span style="color:red;">No Members</span>
<?php endif; ?>
</td>

  <!-- Progress Bar -->
            <td>
                <div class="progress" style="height: 20px; background:#e9ecef; border-radius:4px;">
                    <div class="progress-bar" role="progressbar" 
                         style="width: <?= $t['progress'] ?>%; background-color: <?= $t['progress'] >= 75 ? '#28a745' : ($t['progress'] >= 50 ? '#17a2b8' : ($t['progress'] >= 25 ? '#ffc107' : '#dc3545')) ?>;"
                         aria-valuenow="<?= $t['progress'] ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <?= $t['progress'] ?>%
                    </div>
                </div>
            </td>

 
            

            <td>
                <button class="priority-btn priority-<?= esc($t['priority']) ?>">
                    <?= esc($t['priority']) ?>
                </button>
            </td>

             <!-- ******** -->

             <td>
                  <?= !empty($t['due_date']) 
                    ? date('Y-m-d H:i:s', strtotime($t['due_date'])) 
                    : 'N/A'; 
                  ?>
             </td>


          

            <td>
                <a href="/projects/<?= $project['id'] ?>/tasks/edit/<?= $t['id'] ?>">
                    <button style="background:#007bff;color:white;padding:6px 12px;border:none;border-radius:6px;margin-right:5px;">
                        Edit
                    </button>
                </a>

                <a onclick="return confirm('Are you sure?')" 
                   href="/projects/<?= $project['id'] ?>/tasks/delete/<?= $t['id'] ?>">
                    <button style="background:#dc3545;color:white;padding:6px 12px;border:none;border-radius:6px;">
                        Delete
                    </button>
                </a>
            </td>

            <td>
                <a href="/projects/<?= $project['id'] ?>/tasks/assign/<?= $t['id'] ?>">
                    <button class="btn btn-assign" style="background:#17a2b8;">+ Assign</button>
                </a>
            </td>

            <!-- ⭐ UPDATED MEMBER NAME LIST ⭐ -->
            <td>
                <?php if (!empty($t['assigned_members'])): ?>
                    <ul style="margin:0; padding-left:18px;">
                        <?php foreach ($t['assigned_members'] as $am): ?>
                            <li><?= esc($am['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <span style="color:red;">No Members</span>
                <?php endif; ?>
            </td>

        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="9">No tasks found.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<br>

<a href="/projects" class="add-btn">← Back to Projects</a>

<?= view('layout/footer') ?>
