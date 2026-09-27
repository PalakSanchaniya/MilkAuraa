<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/user_header.php");

$user_id = $_SESSION['user_id'];

// Customer ID
$cust = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT id FROM customers WHERE user_id='$user_id'
"));
$customer_id = $cust['id'];

// Fetch Milk History
$query = mysqli_query($conn,"
    SELECT *
    FROM milk_entries
    WHERE customer_id='$customer_id'
    ORDER BY date DESC, id DESC
");
?>

<style>
/* Unique & Techy Font Import */
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap');

body {
    background: linear-gradient(rgba(15, 23, 42, 0.45), rgba(15, 23, 42, 0.75)), 
                url('../Images/background.webp');
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
    background: rgba(15, 23, 42, 0.65); /* Semi-transparent */
    backdrop-filter: blur(12px); /* Glass blur effect */
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

/* Status Badges */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.morning {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.evening {
    background: rgba(56, 189, 248, 0.15);
    color: #38bdf8;
    border: 1px solid rgba(56, 189, 248, 0.2);
}

.total {
    color: #22c55e;
    font-weight: 700;
    font-size: 16px;
}
</style>

<h1 class="page-title">Milk History</h1>

<div class="table-box">

<table>
    <tr>
        <th>Date</th>
        <th>Shift</th>
        <th>Liters</th>
        <th>Rate</th>
        <th>Total</th>
    </tr>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<tr>
    <td><?php echo date("d M Y", strtotime($row['date'])); ?></td>

    <td>
        <span class="badge <?php echo $row['shift']; ?>">
            <?php echo ucfirst($row['shift']); ?>
        </span>
    </td>

    <td><?php echo number_format($row['liters'],2); ?> L</td>

    <td>₹ <?php echo number_format($row['rate'],2); ?></td>

    <td class="total">
        ₹ <?php echo number_format($row['total_amount'],2); ?>
    </td>
</tr>

<?php } ?>

</table>

</div>

<?php include("../includes/user_footer.php"); ?>