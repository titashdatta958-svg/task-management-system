<?= view('layout/header') ?>

<style>
body {
    font-family: "Poppins", sans-serif;
    background-color: #ff8800ff;
    padding: 40px;
}

.analytics-box {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}
</style>

<h2 class="mb-4">Members Performance Analytics</h2>

<!-- FILTERS -->
<div class="row mb-3">
    <div class="col-md-3">
        <select id="filterYear" class="form-select">
            <?php for ($y = 2022; $y <= date('Y'); $y++): ?>
            <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>>
                <?= $y ?>
            </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="col-md-3">
        <select id="filterMonth" class="form-select">
            <option value="all" <?= ($month=='all')?'selected':'' ?>>All Months</option>
            <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= ($month == $m) ? 'selected' : '' ?>>
                <?= date('F', mktime(0,0,0,$m,1)) ?>
            </option>
            <?php endfor; ?>
        </select>
    </div>
</div>

<canvas id="performanceChart" height="120"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.getElementById("filterYear").addEventListener("change", applyFilter);
document.getElementById("filterMonth").addEventListener("change", applyFilter);

function applyFilter() {
    const year = document.getElementById("filterYear").value;
    const month = document.getElementById("filterMonth").value;
    window.location.href = `/analytics?year=${year}&month=${month}`;
}

const names = [
    <?php foreach ($performance as $p): ?> "<?= $p['name'] ?>",
    <?php endforeach; ?>
];

const values = [
    <?php foreach ($performance as $p): ?>
    <?= round($p['performance'] / 86400, 2) ?>,
    <?php endforeach; ?>
];

// Stable colors
const colors = names.map((_, i) => {
    const palette = [
        "#d6002eff", "#0081d6ff", "#000000ff",
        "#7b3affff", "#ff9f40", "#6c009bff",
        "#00ff6aff", "#470902ff", "#00ffccff"
    ];
    return palette[i % palette.length];
});

new Chart(document.getElementById('performanceChart'), {
    type: 'bar',
    data: {
        labels: names,
        datasets: [{
            label: 'Performance (Days Early / Late)',
            data: values,
            backgroundColor: colors,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Performance (Days)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Members'
                }
            }
        }
    }
});
</script>

<a href="/dashboard" class="btn btn-primary mt-3">← Back to Dashboard</a>

<?= view('layout/footer') ?>