<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

// Status અપડેટ કરો અને is_read ને 0 કરો જેથી યુઝરને નોટિફિકેશન મળે
if(isset($_GET['id']) && isset($_GET['status'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    mysqli_query($conn,"UPDATE orders SET status='$status', is_read=0 WHERE id='$id'");
    echo "<script>window.location.href='orders.php';</script>";
}

$q = mysqli_query($conn,"
    SELECT orders.*, users.name, users.mobile
    FROM orders
    JOIN customers ON orders.customer_id = customers.id
    JOIN users ON customers.user_id = users.id
    ORDER BY orders.id DESC
");
?>

<style>
.page-wrapper { padding: 20px; font-family: 'Inter', sans-serif; }
.box { background:#fff; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
h3 { color: #1e293b; font-weight: 700; margin-bottom: 25px; border-left: 5px solid #6366f1; padding-left: 15px; }
table { width: 100%; border-collapse: collapse; }
th { background: #f8fafc; color: #475569; text-align: left; padding: 15px; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; border-bottom: 2px solid #e2e8f0; }
td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #000 !important; font-weight: 500; }
.status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; text-transform: capitalize; }
.pending { background: #fef3c7; color: #92400e; }
.accepted { background: #dcfce7; color: #166534; }
.rejected { background: #fee2e2; color: #991b1b; }
.btn-action { text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 0.9rem; font-weight: 600; transition: 0.2s; }
.approve { background: #16a34a; color: white !important; margin-right: 5px; }
.reject { background: #dc2626; color: white !important; }
</style>

<div class="page-wrapper">
    <div class="box">
        <h3>Extra Milk Requests</h3>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Liters</th>
                        <th>Status</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($r=mysqli_fetch_assoc($q)){ ?>
                    <tr>
                        <td>
                            <b><?php echo $r['name']; ?></b><br>
                            <small style="color: #64748b;"><?php echo $r['mobile']; ?></small>
                        </td>
                        <td><?php echo date('d M Y', strtotime($r['date'])); ?></td>
                        <td><span style="font-weight: 700;"><?php echo $r['liters']; ?> L</span></td>
                        <td>
                            <span class="status-badge <?php echo $r['status']; ?>">
                                <?php echo $r['status']; ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <?php if($r['status'] == 'pending') { ?>
                                <a class="btn-action approve" href="?id=<?php echo $r['id']; ?>&status=accepted">Accept</a>
                                <a class="btn-action reject" href="?id=<?php echo $r['id']; ?>&status=rejected">Reject</a>
                            <?php } else { ?>
                                <span style="color: #94a3b8; font-size: 0.85rem;">Completed</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>