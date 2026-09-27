<?php
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

$notif_data = null;
$check_col = mysqli_query($conn, "SHOW COLUMNS FROM `orders` LIKE 'is_read'");
if(mysqli_num_rows($check_col) > 0) {
    $check_notify = mysqli_query($conn, "SELECT * FROM orders WHERE customer_id='$customer_id' AND is_read=0 AND (status='accepted' OR status='rejected') LIMIT 1");
    $notif_data = mysqli_fetch_assoc($check_notify);
}


if(isset($_POST['close_notif'])){
    $order_id = $_POST['order_id'];
    mysqli_query($conn, "UPDATE orders SET is_read=1 WHERE id='$order_id'");
    echo "<script>window.location.href='extra_request.php';</script>";
}

if(isset($_POST['submit'])){
    $date = $_POST['date'];
    $liters = $_POST['liters'];
    
  
    if(mysqli_num_rows($check_col) > 0) {
        mysqli_query($conn,"INSERT INTO orders (customer_id,date,liters,status,is_read) VALUES ('$customer_id','$date','$liters','pending', 0)");
    } else {
        mysqli_query($conn,"INSERT INTO orders (customer_id,date,liters,status) VALUES ('$customer_id','$date','$liters','pending')");
    }
    echo "<script>alert('Extra Milk Request Sent Successfully');</script>";
}

$history = mysqli_query($conn,"SELECT * FROM orders WHERE customer_id='$customer_id' ORDER BY id DESC");
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap');

body {
    background: linear-gradient(rgba(15, 23, 42, 0.55), rgba(15, 23, 42, 0.85)), url('../Images/extrareq_background.webp');
    background-size: cover; background-position: center; background-attachment: fixed;
    color: #ffffff; min-height: 100vh; font-family: 'Inter', sans-serif; margin: 0;
}

.notif-box {
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(10px);
    border: 2px solid #38bdf8;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

.btn-ok {
    background: #38bdf8; color: #0f172a; border: none; padding: 8px 25px;
    border-radius: 8px; font-weight: 700; cursor: pointer; margin-top: 10px;
}

.page-title {
    font-family: 'Space Grotesk', sans-serif; font-size: 40px; font-weight: 700;
    margin-bottom: 35px; position: relative; display: inline-block;
}

.page-title::after {
    content: ''; position: absolute; left: 0; bottom: -8px; width: 60%;
    height: 5px; background: linear-gradient(90deg, #38bdf8, transparent); border-radius: 10px;
}

.request-form-box {
    background: rgba(30, 41, 59, 0.5); backdrop-filter: blur(12px);
    border-radius: 20px; padding: 30px; margin-bottom: 35px; border: 1px solid rgba(56, 189, 248, 0.2);
}

.request-form-box form { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }

.request-form-box input {
    background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 12px 18px; border-radius: 10px; color: white; min-width: 200px;
}

.request-form-box button {
    background: #38bdf8; color: #0f172a; border: none; padding: 12px 30px;
    border-radius: 10px; font-weight: 700; cursor: pointer;
}

.table-box {
    background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(15px);
    border-radius: 24px; padding: 30px; border: 1px solid rgba(255, 255, 255, 0.1);
}

table { width: 100%; border-collapse: collapse; min-width: 700px; }
th { color: #38bdf8; padding: 18px 20px; text-align: left; text-transform: uppercase; border-bottom: 2px solid rgba(56, 189, 248, 0.2); font-family: 'Space Grotesk'; }
td { padding: 18px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); color: #f1f5f9; }

.badge { padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; }
.pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
.accepted { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
.rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
</style>

<div class="container" style="padding: 20px;">
    
    <?php if($notif_data): ?>
    <div class="notif-box">
        <h2 style="margin: 0; color: #38bdf8;">Request Update!</h2>
        <p>Your extra milk request for <b><?php echo date('d M Y', strtotime($notif_data['date'])); ?></b> was 
           <b style="color: <?php echo ($notif_data['status'] == 'accepted') ? '#22c55e' : '#ef4444'; ?>;">
              <?php echo strtoupper($notif_data['status']); ?>
           </b> 
        </p>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo $notif_data['id']; ?>">
            <button type="submit" name="close_notif" class="btn-ok">OK</button>
        </form>
    </div>
    <?php endif; ?>

    <h1 class="page-title">Extra Milk Request</h1>

    <div class="request-form-box">
        <h3>Send Request</h3>
        <form method="POST">
            <input type="date" name="date" required>
            <input type="number" step="0.01" name="liters" placeholder="Enter Liters" required>
            <button name="submit" type="submit">Submit Request</button>
        </form>
    </div>

    <div class="table-box">
        <h3>Request History</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Liters</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($history)){ ?>
                <tr>
                    <td><?php echo date("d M Y", strtotime($row['date'])); ?></td>
                    <td><strong><?php echo number_format($row['liters'], 2); ?> L</strong></td>
                    <td>
                        <span class="badge <?php echo strtolower($row['status']); ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../includes/user_footer.php"); ?>