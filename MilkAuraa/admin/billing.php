<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

// 🔴 DELETE PAYMENT
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM payments WHERE id='$id'");
    echo "<script>alert('Payment Deleted Successfully'); window.location.href='billing.php';</script>";
}

// Fetch customers
$customers_query = "SELECT customers.id, users.name FROM customers JOIN users ON customers.user_id = users.id";
$customers = mysqli_query($conn, $customers_query);

// INSERT PAYMENT
if (isset($_POST['submit'])) {
    $customer_id = $_POST['customer_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    mysqli_query($conn, "INSERT INTO payments (customer_id, amount_paid, payment_date) VALUES ('$customer_id', '$amount', '$date')");
    echo "<script>alert('Payment Recorded Successfully'); window.location.href='billing.php';</script>";
}
?>

<style>
/* --- UI STYLING (Screen) --- */
.page-wrapper { 
    width: 100%; 
    padding: 30px; 
    font-family: 'Segoe UI', sans-serif; 
    background: #0d1b2a; /* Soft background for better separation */
}

<div class="page-wrapper">
    <div class="box no-print"> ... </div>

    <div class="box no-print"> ... </div>

    <?php if(isset($_GET['customer_id'])): ?>
        <div class="box" id="billArea"> 
            ... 
        </div>
        <div style="height: 20px;" class="no-print"></div> 
    <?php endif; ?>

    <div class="box no-print">
        <h3>Recent Payment History</h3>
        ...
    </div>
</div>

.forms-container { 
    display: flex; 
    flex-direction: column; 
    gap: 40px; /* Strong gap between sections */
    margin-bottom: 40px; 
}

.box {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
    margin-bottom: 40px; /* આ લાઇન ઉમેરવાથી બે બોક્સ વચ્ચે ગેપ આવશે */
}

/* GREEN BORDER HEADING - EXACTLY AS REQUESTED */
.box h3 {
    color: #1e293b;
    font-size: 1.4rem;
    margin-bottom: 25px;
    font-weight: 700;
    border-left: 5px solid #16a34a; /* Classic Green Sidebar */
    padding-left: 15px;
}

input, select {
    width: 100%;
    padding: 12px;
    margin: 8px 0 20px 0;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background: #fff;
}

button {
    width: 100%;
    padding: 14px;
    background: #16a34a;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

/* --- PROFESSIONAL TABLE STYLING --- */
.bill-table { 
    width: 100%; 
    border-collapse: collapse; 
    margin: 20px 0;
    border: 1px solid #e2e8f0;
}

.bill-table th { 
    background: #f8fafc; 
    padding: 15px; 
    text-align: left; 
    border-bottom: 2px solid #e2e8f0;
    font-size: 13px; 
    color: #64748b;
    text-transform: uppercase;
}

.bill-table td { 
    padding: 15px; 
    border-bottom: 1px solid #f1f5f9; 
    color: #1e293b;
    font-size: 15px;
}

/* TOTALS SECTION ROW STYLING */
.total-row td {
    padding: 12px 15px;
    font-weight: 700;
}

.label-cell {
    text-align: right;
    padding-right: 30px !important;
    background: #fdfdfd;
}

.amount-cell {
    text-align: center;
    width: 150px;
    border-left: 1px solid #f1f5f9;
}

/* STATUS COLORS */
.due-amount { color: #e11d48; border-bottom: 2px solid #e11d48; }
.paid-amount { color: #16a34a; background: #f0fdf4; }

/* --- BILLING TABLE STYLING --- */
.bill-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
.bill-table th { background: #f8fafc; padding: 15px; text-align: left; border: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
.bill-table td { padding: 15px; border: 1px solid #e2e8f0; color: #1e293b; }

.bill-info-grid { display: flex; justify-content: space-between; margin-bottom: 25px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; }

/* --- 🎯 PRINT OPTIMIZATION (No Red Box & Single Page) --- */
@media print {
    header, footer, nav, .sidebar, .no-print, .forms-container, .box:not(#billArea) { 
        display: none !important; 
    }
    body, html { background: #fff !important; margin: 0; padding: 0; }
    #billArea {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        padding: 40px !important;
        box-shadow: none !important;
        border: none !important;
        visibility: visible !important;
    }
}
</style>

<div class="page-wrapper">

    <div class="forms-container no-print">
        <div class="box">
            <h3>Record Payment</h3>
            <form method="POST">
                <select name="customer_id" required>
                    <option value="">Select Customer</option>
                    <?php 
                    mysqli_data_seek($customers, 0);
                    while($c = mysqli_fetch_assoc($customers)) { ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                    <?php } ?>
                </select>
                <input type="number" step="0.01" name="amount" placeholder="Amount Paid (₹)" required>
                <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
                <button type="submit" name="submit">Save Payment</button>
            </form>
        </div>

        <div class="box">
            <h3>Download Customer Bill</h3>
            <form method="GET">
                <select name="customer_id" required>
                    <option value="">Select Customer</option>
                    <?php 
                    mysqli_data_seek($customers, 0);
                    while($c = mysqli_fetch_assoc($customers)) { ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                    <?php } ?>
                </select>
                <input type="date" name="from_date" required>
                <input type="date" name="to_date" required>
                <button type="submit" style="background:#4f46e5;">Generate Bill</button>
            </form>
        </div>
    </div>

    <?php
    if(isset($_GET['customer_id']) && isset($_GET['from_date']) && isset($_GET['to_date'])){
        $cid  = $_GET['customer_id'];
        $from = $_GET['from_date'];
        $to   = $_GET['to_date'];

        $cust = mysqli_fetch_assoc(mysqli_query($conn,"SELECT users.name, users.mobile FROM customers JOIN users ON customers.user_id = users.id WHERE customers.id='$cid'"));
        $milk = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(liters) as liters, SUM(total_amount) as total FROM milk_entries WHERE customer_id='$cid' AND date BETWEEN '$from' AND '$to'"));
        $pay = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount_paid) as paid FROM payments WHERE customer_id='$cid' AND payment_date BETWEEN '$from' AND '$to'"));

        $total = $milk['total'] ?? 0;
        $liters = $milk['liters'] ?? 0;
        $paid = $pay['paid'] ?? 0;
        $balance = $total - $paid;
    ?>

    <div class="box" id="billArea">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 3px solid #1e293b; padding-bottom: 15px; margin-bottom: 25px;">
            <div>
                <h1 style="margin:0; color:#1e293b; letter-spacing: 1px;">INVOICE</h1>
                <p style="margin:5px 0 0 0; color:#64748b;">Milk Supply Report</p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin:0; font-weight:800; color:#4f46e5;">MilkAuraa</h2>
                <p style="margin:2px 0 0 0; font-size:13px;">Date: <?= date('d-m-Y') ?></p>
            </div>
        </div>

        <div class="bill-info-grid">
            <div>
                <p style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: bold;">Customer Information</p>
                <p style="font-size: 18px; color: #1e293b; font-weight: 700; margin: 5px 0;"><?= $cust['name'] ?></p>
                <p style="margin:0;">Phone: <?= $cust['mobile'] ?></p>
            </div>
            <div style="text-align: right;">
                <p style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: bold;">Billing Period</p>
                <p style="font-size: 15px; margin: 5px 0;"><strong>From:</strong> <?= date('d M Y', strtotime($from)) ?></p>
                <p style="margin:0;"><strong>To:</strong> <?= date('d M Y', strtotime($to)) ?></p>
            </div>
        </div>

        <table class="bill-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Qty (Ltrs)</th>
                    <th style="text-align: center;">Total Amount</th>
                
                </tr>
            </thead>
<tbody>
    <tr>
        <td>Fresh Milk Supply Entries</td>
        <td style="text-align: center; font-weight: bold;"><?= number_format($liters, 2) ?> L</td>
        <td style="text-align: center;">₹ <?= number_format($total, 2) ?></td>

    </tr>

    <tr style="background: #fff; font-weight: 700;">
        <td colspan="3" style="text-align: right; padding-right: 30px; color: #1e293b;">Total Balance Due:</td>
        <td style="text-align: center; color: #e11d48; border-bottom: 2px solid #e11d48;">₹ <?= number_format($balance, 2) ?></td>
    </tr>

    <tr style="background: #f8fafc; font-weight: 800; font-size: 18px;">
        <td colspan="3" style="text-align: right; padding-right: 30px;">Total Paid Amount:</td>
        <td style="text-align: center; color: #16a34a;">₹ <?= number_format($paid, 2) ?></td>
    </tr>
</tbody>
        </table>

        <div style="margin-top: 50px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <p style="font-size: 12px; color: #94a3b8;">This is a computer-generated bill. No signature required.</p>
        </div>

        <div class="no-print" style="margin-top: 30px; text-align: right;">
            <button onclick="window.print()" style="background:#2563eb; width: auto; padding: 12px 40px; font-size: 16px;">
                Print / Download Bill
            </button>
        </div>
    </div>
    <?php } ?>

    <div class="box no-print">
        <h3>Recent Payment History</h3>
        <table class="bill-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Amount Paid</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $history = mysqli_query($conn, "SELECT payments.*, users.name FROM payments JOIN customers ON payments.customer_id = customers.id JOIN users ON customers.user_id = users.id ORDER BY payment_date DESC LIMIT 10");
            while($row = mysqli_fetch_assoc($history)) { ?>
            <tr>
                <td style="font-weight: 600; color: #4f46e5;"><?= $row['name'] ?></td>
                <td style="color: #16a34a; font-weight: 700;">₹ <?= number_format($row['amount_paid'], 2) ?></td>
                <td><?= date('d-M-Y', strtotime($row['payment_date'])) ?></td>
                <td>
                    <a href="billing.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete record?')" style="color: #e11d48; text-decoration: none; font-weight: bold;">Delete</a>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php include("../includes/footer.php"); ?>