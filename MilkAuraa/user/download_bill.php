<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/user_header.php");

$user_id = $_SESSION['user_id'];

$cust = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id FROM customers WHERE user_id='$user_id'
"));
$customer_id = $cust['id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT users.name, users.mobile
FROM customers
JOIN users ON customers.user_id = users.id
WHERE customers.id='$customer_id'
"));
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap');

body{
    background:linear-gradient(rgba(15,23,42,.45),rgba(15,23,42,.75)),
    url('../Images/background.webp');
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
    color:#fff;
    min-height:100vh;
    margin:0;
    font-family:'Inter',sans-serif;
}

/* Title */
.page-title{
    font-family:'Space Grotesk',sans-serif;
    font-size:40px;
    font-weight:700;
    margin:30px 0;
    position:relative;
    display:inline-block;
}

.page-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-6px;
    width:60%;
    height:4px;
    border-radius:10px;
    background:linear-gradient(90deg,#38bdf8,transparent);
}

/* Glass Box */
.table-box{
    background:rgba(15,23,42,.65);
    backdrop-filter:blur(12px);
    border-radius:22px;
    padding:28px;
    border:1px solid rgba(255,255,255,.08);
    box-shadow:0 25px 50px rgba(0,0,0,.45);
    overflow:auto;
    margin-bottom:35px;
}

/* Form */
.row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

label{
    display:block;
    color:#cbd5e1;
    margin-bottom:8px;
    font-size:14px;
}

input{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(17,24,39,.8);
    color:#fff;
    outline:none;
}

button{
    margin-top:18px;
    background:#38bdf8;
    color:#08111f;
    border:none;
    padding:14px 26px;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}

button:hover{
    transform:translateY(-2px);
}

/* ===== PROFESSIONAL BILL ===== */
.bill-box{
    background:#ffffff;
    color:#111827;
    border-radius:22px;
    padding:40px;
    box-shadow:0 25px 60px rgba(0,0,0,.30);
    margin-bottom:40px;
}

.bill-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    border-bottom:2px solid #e5e7eb;
    padding-bottom:18px;
    margin-bottom:25px;
}

.brand{
    font-size:32px;
    font-weight:800;
    color:#0f172a;
}

.tagline{
    color:#64748b;
    font-size:14px;
    margin-top:5px;
}

.invoice-meta{
    text-align:right;
    font-size:14px;
    color:#374151;
    line-height:1.8;
}

.info-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
    margin-bottom:25px;
}

.info-card{
    background:#f8fafc;
    padding:18px;
    border-radius:14px;
}

.info-title{
    font-size:12px;
    color:#64748b;
    text-transform:uppercase;
    margin-bottom:8px;
    font-weight:700;
}

.info-value{
    font-size:18px;
    font-weight:700;
    color:#111827;
}

/* Bill Table */
.bill-table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

.bill-table th{
    background:#0f172a;
    color:#fff;
    padding:15px;
    text-align:left;
    font-size:13px;
    text-transform:uppercase;
}

.bill-table td{
    padding:15px;
    border-bottom:1px solid #e5e7eb;
    color:#111827;
    font-size:15px;
}

.right{
    text-align:right;
}

.green{
    color:#16a34a;
    font-weight:700;
}

.red{
    color:#dc2626;
    font-weight:800;
    font-size:18px;
}

.total-row{
    background:#f8fafc;
}

.bill-footer{
    margin-top:28px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:#6b7280;
    font-size:14px;
}

.print-btn{
    background:#2563eb;
    color:#fff;
}

.print-btn:hover{
    background:#1d4ed8;
}

@media(max-width:768px){
    .row,.info-grid{
        grid-template-columns:1fr;
    }

    .bill-header{
        flex-direction:column;
        gap:15px;
    }

    .invoice-meta{
        text-align:left;
    }
}

/* PRINT */
@media print{
    body *{
        visibility:hidden;
    }

    #billArea,#billArea *{
        visibility:visible;
    }

    #billArea{
        position:absolute;
        top:0;
        left:0;
        width:100%;
        margin:0;
        box-shadow:none;
    }

    .no-print{
        display:none !important;
    }
}
</style>

<h1 class="page-title">Download Bill</h1>

<div class="table-box">
<form method="GET">
    <div class="row">
        <div>
            <label>From Date</label>
            <input type="date" name="from_date" required>
        </div>

        <div>
            <label>To Date</label>
            <input type="date" name="to_date" required>
        </div>
    </div>

    <button type="submit">Generate Bill</button>
</form>
</div>

<?php
if(isset($_GET['from_date']) && isset($_GET['to_date'])){

$from = $_GET['from_date'];
$to   = $_GET['to_date'];

$milk = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(liters) as liters,
SUM(total_amount) as total
FROM milk_entries
WHERE customer_id='$customer_id'
AND date BETWEEN '$from' AND '$to'
"));

$pay = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount_paid) as paid
FROM payments
WHERE customer_id='$customer_id'
AND payment_date BETWEEN '$from' AND '$to'
"));

$liters  = $milk['liters'] ?? 0;
$total   = $milk['total'] ?? 0;
$paid    = $pay['paid'] ?? 0;
$balance = $total - $paid;
?>

<div class="bill-box" id="billArea">

<div class="bill-header">
    <div>
        <div class="brand">MilkAuraa</div>
        <div class="tagline">Fresh Milk • Trusted Service</div>
    </div>

    <div class="invoice-meta">
        <b>Invoice Date:</b> <?php echo date('d M Y'); ?><br>
        <b>Invoice No:</b> INV-<?php echo rand(1000,9999); ?>
    </div>
</div>

<div class="info-grid">
    <div class="info-card">
        <div class="info-title">Bill To</div>
        <div class="info-value"><?php echo $user['name']; ?></div>
        <div><?php echo $user['mobile']; ?></div>
    </div>

    <div class="info-card">
        <div class="info-title">Billing Period</div>
        <div class="info-value">
            <?php echo date('d M Y',strtotime($from)); ?>
        </div>
        <div>to <?php echo date('d M Y',strtotime($to)); ?></div>
    </div>
</div>

<table class="bill-table">
<tr>
    <th>Description</th>
    <th class="right">Amount</th>
</tr>

<tr>
    <td>Total Milk Quantity (<?php echo number_format($liters,2); ?> L)</td>
    <td class="right">₹ <?php echo number_format($total,2); ?></td>
</tr>

<tr>
    <td>Amount Paid</td>
    <td class="right green">₹ <?php echo number_format($paid,2); ?></td>
</tr>

<tr class="total-row">
    <td><b>Remaining Balance</b></td>
    <td class="right red">₹ <?php echo number_format($balance,2); ?></td>
</tr>
</table>

<div class="bill-footer">
    <div>Thank you for choosing MilkAuraa.</div>

    <div class="no-print">
        <button onclick="window.print()" class="print-btn">
            Download / Print PDF
        </button>
    </div>
</div>

</div>

<?php } ?>

<?php include("../includes/user_footer.php"); ?>