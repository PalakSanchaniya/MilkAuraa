<?php
// સેશન ચાલુ કરો જો ન હોય તો
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../config/db.php");
include("../includes/auth.php");
include("../includes/user_header.php");

$user_id = $_SESSION['user_id'] ?? 0; 

if ($user_id == 0) {
    echo "<script>window.location.href='../login.php';</script>";
    exit();
}

$cust_res = mysqli_query($conn, "SELECT id FROM customers WHERE user_id='$user_id'");
$cust = mysqli_fetch_assoc($cust_res);
$customer_id = $cust['id'] ?? 0;

$user_res = mysqli_query($conn, "SELECT name FROM users WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($user_res);
$display_name = $user_data['name'] ?? "User";

$today = date("Y-m-d");
$week = date("Y-m-d", strtotime("-7 days"));
$month = date("Y-m-01");

// Today's Data
$today_data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(liters) as liters, SUM(total_amount) as amount 
    FROM milk_entries 
    WHERE customer_id='$customer_id' AND date='$today'
")) ?? ['liters' => 0, 'amount' => 0];

// Weekly Data
$week_data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(liters) as liters, SUM(total_amount) as amount 
    FROM milk_entries 
    WHERE customer_id='$customer_id' AND date >= '$week'
")) ?? ['liters' => 0, 'amount' => 0];

// Monthly Data
$month_data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(liters) as liters, SUM(total_amount) as amount 
    FROM milk_entries 
    WHERE customer_id='$customer_id' AND date >= '$month'
")) ?? ['liters' => 0, 'amount' => 0];

// Balance Calculation
$total_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM milk_entries WHERE customer_id='$customer_id'"));
$paid_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount_paid) as paid FROM payments WHERE customer_id='$customer_id'"));

$total_amt = $total_res['total'] ?? 0;
$paid_amt = $paid_res['paid'] ?? 0;
$balance = $total_amt - $paid_amt;
?>

<style>
/* Unique Font Import for Welcome Message */
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap');

body {
    background: linear-gradient(rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.8)), 
                url('../Images/dashboard_background.webp');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #ffffff;
    min-height: 100vh;
    margin: 0;
}

/* Premium Dark Hero Box with Glass Effect */
.hero {
    background: rgba(30, 41, 59, 0.4); /* ટ્રાન્સપરન્ટ જેથી પાછળની ઈમેજ દેખાય */
    backdrop-filter: blur(12px); /* બ્લર ઈફેક્ટ */
    border-radius: 24px;
    padding: 60px;
    margin-bottom: 35px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

/* Welcome Text Fonts Change */
.hero h1 { 
    font-family: 'Space Grotesk', sans-serif; /* Unique Font */
    font-size: 45px; 
    margin-bottom: 10px; 
    font-weight: 700; 
    color: #fff;
    letter-spacing: -1px;
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.hero p { 
    color: #cbd5e1; 
    font-size: 18px; 
    font-weight: 400;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 25px;
}

/* Glassmorphism Cards */
.card {
    background: rgba(30, 41, 59, 0.6); 
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 30px;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-8px);
    background: rgba(30, 41, 59, 0.8);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    border-color: rgba(56, 189, 248, 0.3);
}

.card h3 { 
    font-size: 13px; 
    color: #94a3b8; 
    text-transform: uppercase; 
    letter-spacing: 1.5px; 
    margin-bottom: 15px; 
}

.value { 
    font-size: 34px; 
    font-weight: 700; 
    color: #f8fafc; 
    margin-bottom: 5px; 
}

.sub { 
    color: #38bdf8; 
    font-size: 15px; 
    font-weight: 600; 
}

/* Border Accents */
.today { border-left: 5px solid #22c55e; }
.week { border-left: 5px solid #38bdf8; }
.month { border-left: 5px solid #f59e0b; }
.balance { border-left: 5px solid #ef4444; }</style>

<div class="hero">
    <h1>Welcome, <?php echo htmlspecialchars($display_name); ?> 👋🏻</h1>
    <p>Fresh milk updates, records and balance in one place.</p>
</div>

<div class="grid">
    <div class="card today">
        <h3>Today's Milk</h3>
        <div class="value"><?php echo number_format($today_data['liters'] ?? 0, 2); ?> L</div>
        <div class="sub">₹ <?php echo number_format($today_data['amount'] ?? 0, 2); ?></div>
    </div>

    <div class="card week">
        <h3>Last 7 Days</h3>
        <div class="value"><?php echo number_format($week_data['liters'] ?? 0, 2); ?> L</div>
        <div class="sub">₹ <?php echo number_format($week_data['amount'] ?? 0, 2); ?></div>
    </div>

    <div class="card month">
        <h3>This Month</h3>
        <div class="value"><?php echo number_format($month_data['liters'] ?? 0, 2); ?> L</div>
        <div class="sub">₹ <?php echo number_format($month_data['amount'] ?? 0, 2); ?></div>
    </div>

    <div class="card balance">
        <h3>Pending Balance</h3>
        <div class="value">₹ <?php echo number_format($balance, 2); ?></div>
        <div class="sub">Current Due Amount</div>
    </div>
</div>

<?php include("../includes/user_footer.php"); ?>