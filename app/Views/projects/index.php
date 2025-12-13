<?= view('layout/header') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>

    <style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #ff8800ff;
        padding: 40px;
    }

    h1 {
        font-weight: 600;
        margin-bottom: 20px;
        color: #000;
    }

    /* Add Button */
    .add-btn {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 10px 18px;
        font-weight: 500;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
        margin-bottom: 20px;
    }

    .add-btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    thead {
        background: #000;
        color: #fff;
    }

    th,
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    /* tr:hover {
            background: #000000ff;
        } */

    /* Action Buttons */
    .btn {
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 6px;
        color: white;
        text-decoration: none;
        margin-right: 6px;
        transition: 0.3s;
    }

    .btn-edit {
        background-color: #28a745;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn:hover {
        opacity: 0.85;
        transform: translateY(-2px);
    }


    .name1 {
        background-color: #00063fff;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: 0.3s;

    }

    .name1:hover {
        opacity: 0.85;
        transform: translateY(-2px);
    }
    .project-row:hover {
        background-color: #b5fff5ff;
    }
    </style>
</head>

<body>

    <h1>Projects</h1>


    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/index.php/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Projects</li>
        </ol>
    </nav>


    <?php if(session()->getFlashdata('success')): ?>
    <div style="color:green; margin-bottom:15px; font-weight:bold;">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <a href="/projects/create" class="add-btn">+ Create New Project</a>


<style>
.filter-box {
    display: none;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px #ccc;
}
.filter-btn {
    background: #008cff;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.filter-close {
    float: right;
    cursor: pointer;
    font-size: 18px;
}
</style>

<script>
function toggleFilter() {
    let box = document.getElementById('filterBox');
    box.style.display = (box.style.display === "block") ? "none" : "block";
}
</script>


<button class="filter-btn" onclick="toggleFilter()">Filter</button>

<div id="filterBox" class="filter-box">
    <span class="filter-close" onclick="toggleFilter()">✖</span>

    <form method="GET" action="">
        <label><b>Project Name</b></label>
        <input type="text" name="name" value="<?= esc($filterName) ?>" class="form-control">

        <label><b>Start Date</b></label>
        <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="form-control">

        <label><b>End Date</b></label>
        <input type="date" name="end_date" value="<?= esc($endDate) ?>" class="form-control">

        <button type="submit" class="filter-btn" style="margin-top:10px;">Apply Filter</button>
    </form>
</div>





    <?php if (empty($projects)): ?>
    <p>No projects yet.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <?php $i = 1; ?>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Start - End</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($projects as $p): ?>
            <tr class="project-row" onclick="window.location='/projects/<?= $p['id'] ?>/tasks'">
                <td><?= $i++ ?></td>  <!-- Auto-increment number -->

                <td>
                
                    <a href="/projects/<?= $p['id'] ?>/tasks" style="font-weight:500; color:#000;">
                        <button class="name1"><?= esc($p['name']) ?></button>
                    </a>

                </td>

                <td style="max-width: 350px;">
                    <?= esc($p['description']) ?>
                </td>

                <td>
                    <?= $p['start_date'] ? esc($p['start_date']) : '—' ?> —
                    <?= $p['end_date'] ? esc($p['end_date']) : '—' ?>
                </td>


                <td>
                    <a href="/projects/edit/<?= $p['id'] ?>" class="btn btn-edit">Edit</a>
                    <a href="/projects/delete/<?= $p['id'] ?>" class="btn btn-delete"
                        onclick="return confirm('Delete project?')">
                        Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</body>

</html>

<?= view('layout/footer') ?>