<?= view('layout/header') ?>

<style>
body {
    font-family: "Poppins", sans-serif;
    background-color: #ff8800ff;
    padding: 40px;
}

/* Add Member Button */
.add-btn {
    display: inline-block;
    background-color: #007bff;
    color: white;
    font-weight: 500;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
    margin-bottom: 1px;
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
    background-color: #000;
    color: white;
}

th,
td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

.project-row:hover {
    background-color: #b5fff5ff;
}

/* Action Buttons */
.btn {
    padding: 6px 12px;
    font-size: 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    color: white;
    margin-right: 5px;
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
</style>


<h1>Members List</h1>
<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/index.php/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Members</li>
    </ol>
</nav>


<!-- // Filter Box -->

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
    font-size: 20px;
}
</style>

<script>
function toggleFilter() {
    let f = document.getElementById("filterBox");
    f.style.display = (f.style.display === "block") ? "none" : "block";
}
</script>

<button class="filter-btn" onclick="toggleFilter()">Filter</button>

<div id="filterBox" class="filter-box">
    <span class="filter-close" onclick="toggleFilter()">✖</span>

    <form action="" method="GET">

        <label><b>Member Name</b></label>
        <input type="text" name="name" value="<?= esc($filterName ?? '') ?>" class="form-control">

        <label><b>Email</b></label>
        <input type="text" name="email" value="<?= esc($filterEmail ?? '') ?>" class="form-control">

        <button type="submit" class="filter-btn" style="margin-top:10px;">Apply Filter</button>
    </form>
</div>

<!-- FILTER -->










<a href="/members/create" class="add-btn">
    + Add Member
</a>

<br><br>

<table>
    <thead>
        <tr>
            <?php $i = $start; ?>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody class="table-group-divider">
        <?php foreach ($members as $m): ?>
        <tr class="project-row">
            <td><?= ++$i ?></td>
            <td><?= esc($m['name']) ?></td>
            <td><?= esc($m['email']) ?></td>

            <td>
                <a href="/members/edit/<?= $m['id'] ?>">
                    <button class="btn btn-edit">Edit</button>
                </a>

                <a href="/members/delete/<?= $m['id'] ?>" onclick="return confirm('Delete this member?')">
                    <button class="btn btn-delete">Delete</button>
                </a>
                <a href="/admin/members/reset-password/<?= $m['id'] ?>">
                    <button class="btn btn-warning btn-sm">Reset Password</button>
                </a>






            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- PAGINATION --> <?php $currentPage = $pager->getCurrentPage(); $totalPages = $pager->getPageCount(); ?>
<div class="mt-3 custom-pagination d-flex justify-content-center"> <?php if ($currentPage > 1): ?>
    <a class="on" href="?page=<?= $currentPage - 1 ?>">Previous</a>
    <?php endif; ?>
    <div class="on1"><?= $pager->links() ?>
    </div>
    <?php if ($currentPage < $totalPages): ?>
    <a class="on" href="?page=<?= $currentPage + 1 ?>">Next</a>
    <?php endif; ?>
</div>

<!--PAGINATION -->

<style>
/* Wrapper for number links */
.on1 a,
.on1 strong {
    padding: 8px 12px;
    margin: 0 4px;
    background: #f1f1f1;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    border: 1px solid #ddd;
    transition: 0.2s;
}

/* Hover effect for numbers */
.on1 a:hover {
    background: #007bff;
    color: #fff;
}

/* Active page number (CodeIgniter prints <strong>) */
.on1 strong {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* Previous / Next Buttons */
.on {
    padding: 8px 12px;
    background: #f1f1f1;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    border: 1px solid #ddd;
    transition: 0.2s;
    margin: 0 10px;
}

/* Hover for Prev/Next */
.on:hover {
    background: #007bff;
    color: white;
}
</style>


<?= view('layout/footer') ?>