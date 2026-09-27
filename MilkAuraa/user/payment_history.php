<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/user_header.php");

/* USER CUSTOMER ID */
$cust = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT id FROM customers WHERE user_id='$user_id'
"));

$customer_id = $cust['id'];

/* PAYMENT HISTORY */
$payments = mysqli_query($conn,"
    SELECT *
    FROM payments
    WHERE customer_id='$customer_id'
    ORDER BY payment_date DESC, id DESC
");
?>

<style>
/* Unique & Techy Font Import */
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap');

body {
    background: linear-gradient(rgba(15, 23, 42, 0.45), rgba(15, 23, 42, 0.75)), 
                url('../Images/background.webp'); /* બીજા પેજ જેવું બેકગ્રાઉન્ડ */
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #ffffff;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
    margin: 0;
}

.page-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 40px;
    font-weight: 700;
    color: #ffffff;
    margin: 30px 0;
    position: relative;
    display: inline-block;
    letter-spacing: -1px;
}

.page-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 60%;
    height: 4px;
    background: linear-gradient(90deg, #38bdf8, transparent);
    border-radius: 10px;
}

.table-box {
    background: rgba(15, 23, 42, 0.65); /* Semi-transparent Glass Effect */
    backdrop-filter: blur(12px); 
    border-radius: 20px;
    padding: 25px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
    overflow: auto;
    margin-bottom: 50px;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

th {
    background: rgba(17, 24, 39, 0.8);
    color: #38bdf8;
    padding: 18px 15px;
    text-align: left;
    font-size: 13px;
    text-transform: uppercase;
    font-family: 'Space Grotesk', sans-serif;
    letter-spacing: 1px;
    border-bottom: 2px solid rgba(56, 189, 248, 0.2);
}

td {
    padding: 18px 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: #e5e7eb;
    font-size: 15px;
}

tr:hover {
    background: rgba(56, 189, 248, 0.04);
    transition: 0.3s ease;
}

/* Status Badge - બીજા કોડ જેવું */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.success-badge {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.amount-total {
    color: #22c55e;
    font-weight: 700;
    font-size: 16px;
}

.empty-box {
    text-align: center;
    color: #cbd5e1;
    padding: 35px;
    font-size: 16px;
}
</style>

<div class="page-wrapper" style="padding: 0 30px;">

    <h1 class="page-title">Payment History</h1>

    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Payment Date</th>
                    <th>Amount Paid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                if(mysqli_num_rows($payments) > 0){
                    while($row = mysqli_fetch_assoc($payments)){
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    
                    <td><?php echo date('d M Y', strtotime($row['payment_date'])); ?></td>

                    <td class="amount-total">
                        ₹ <?php echo number_format($row['amount_paid'], 2); ?>
                    </td>

                    <td>
                        <span class="badge success-badge">Success</span>
                    </td>
                </tr>
                <?php
                    }
                } else {
                ?>
                <tr>
                    <td colspan="4" class="empty-box">No Payment History Found</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php include("../includes/user_footer.php"); ?>