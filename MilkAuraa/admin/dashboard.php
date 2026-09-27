<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

/* --- DATA FETCH --- */
$customer_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM customers"));
$total_customers = $customer_data['total'];

$today = date("Y-m-d");
$milk_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(liters) as total_liters FROM milk_entries WHERE date='$today'"));
$total_liters = $milk_data['total_liters'] ?? 0;

$revenue_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total_amount FROM milk_entries WHERE date='$today'"));
$total_revenue = $revenue_data['total_amount'] ?? 0;

/* --- DAILY CHART (LAST 7 DAYS) --- */
$daily = mysqli_query($conn, "SELECT date, SUM(total_amount) as total FROM milk_entries GROUP BY date ORDER BY date DESC LIMIT 7");
$dates = []; $amounts = [];
while($d = mysqli_fetch_assoc($daily)){
    $dates[] = date('d M', strtotime($d['date']));
    $amounts[] = $d['total'];
}

/* --- MONTHLY CHART --- */
$monthly = mysqli_query($conn, "SELECT MONTHNAME(date) as month, SUM(total_amount) as total FROM milk_entries GROUP BY MONTH(date) ORDER BY MONTH(date)");
$months = []; $month_amounts = [];
while($m = mysqli_fetch_assoc($monthly)){
    $months[] = $m['month'];
    $month_amounts[] = $m['total'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .page-wrapper { padding: 25px; background: #f8fafc; }
    
    /* Stats Cards */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    .card {
        padding: 25px;
        border-radius: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        transition: 0.3s;
    }
    .card:hover { transform: translateY(-5px); }
    .card-1 { background: linear-gradient(135deg, #4f46e5, #818cf8); }
    .card-2 { background: linear-gradient(135deg, #0ea5e9, #38bdf8); }
    .card-3 { background: linear-gradient(135deg, #10b981, #34d399); }
    
    .card h3 { font-size: 0.9rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
    .card p { font-size: 2rem; font-weight: 800; margin: 0; }

    /* Chart Boxes */
    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr;
        gap: 25px;
    }
    .chart-box {
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .chart-box h3 {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .chart-box h3::before {
        content: '';
        width: 4px;
        height: 20px;
        background: #4f46e5;
        border-radius: 10px;
    }

    @media (max-width: 992px) { .charts-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-wrapper">
    <div class="cards">
        <div class="card card-1">
            <h3>Total Active Customers</h3>
            <p><?php echo number_format($total_customers); ?></p>
        </div>
        <div class="card card-2">
            <h3>Today's Collection</h3>
            <p><?php echo number_format($total_liters, 1); ?> <small style="font-size: 1rem;">Ltrs</small></p>
        </div>
        <div class="card card-3">
            <h3>Today's Earnings</h3>
            <p>₹ <?php echo number_format($total_revenue, 2); ?></p>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-box">
            <h3>Revenue Trends (Last 7 Days)</h3>
            <canvas id="dailyChart" height="250"></canvas>
        </div>

        <div class="chart-box">
            <h3>Monthly Overview</h3>
            <canvas id="monthlyChart" height="250"></canvas>
        </div>
    </div>
</div>

<script>
// --- Professional Gradient Configuration ---
const ctx1 = document.getElementById('dailyChart').getContext('2d');
const gradient1 = ctx1.createLinearGradient(0, 0, 0, 400);
gradient1.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
gradient1.addColorStop(1, 'rgba(79, 70, 229, 0)');

// Daily Revenue Line Chart
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_reverse($dates)); ?>,
        datasets: [{
            label: 'Daily Revenue (₹)',
            data: <?php echo json_encode(array_reverse($amounts)); ?>,
            borderColor: '#4f46e5',
            backgroundColor: gradient1,
            borderWidth: 3,
            fill: true,
            tension: 0.4, // Smooth Curves
            pointBackgroundColor: '#fff',
            pointBorderColor: '#4f46e5',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#64748b' } },
            x: { grid: { display: false }, ticks: { color: '#64748b' } }
        }
    }
});

// Monthly Revenue Bar Chart
const ctx2 = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Monthly Total',
            data: <?php echo json_encode($month_amounts); ?>,
            backgroundColor: '#0ea5e9',
            borderRadius: 8,
            barThickness: 25
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#64748b' } },
            x: { grid: { display: false }, ticks: { color: '#64748b' } }
        }
    }
});
</script>

<?php include("../includes/footer.php"); ?>