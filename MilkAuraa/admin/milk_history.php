<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

// DELETE ENTRY
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM milk_entries WHERE id='$id'");
    echo "<script>alert('Deleted!'); window.location.href='milk_history.php';</script>";
}

$query = mysqli_query($conn, "
    SELECT milk_entries.*, users.name 
    FROM milk_entries 
    JOIN customers ON milk_entries.customer_id = customers.id
    JOIN users ON customers.user_id = users.id
    ORDER BY date DESC
");
?>

<style>
.table-container {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
}

.table-box {
    background: #ffffff !important; 
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 100%;
}

.table-box h3 {
    color: #1e293b;
    font-size: 1.6rem;
    margin-bottom: 25px;
    font-weight: 700;
    border-left: 5px solid #667eea;
    padding-left: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

thead th {
    background: #f1f5f9;
    color: #1e293b;
    text-align: left;
    padding: 15px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
}

tbody td {
    padding: 15px;
    border-bottom: 1px solid #f1f5f9;
    color: #000000 !important;
    font-size: 1rem;
    font-weight: 500;
}

tbody tr:hover {
    background-color: #f8fafc;
    transition: 0.2s;
}

.total-amount {
    font-weight: 700;
    color: #1e293b;
}

.customer-name {
    color: #6b46c1;
    font-weight: 600;
}

.delete-btn {
    color: red;
    font-weight: bold;
    text-decoration: none;
}
</style>

<div class="table-container">
    <div class="table-box">
        <h3>Milk History</h3>

        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th>Shift</th>
                    <th>Liters</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td class="customer-name"><?php echo $row['name']; ?></td>
                    <td><?php echo date('d-M-Y', strtotime($row['date'])); ?></td>
                    <td><?php echo ucfirst($row['shift']); ?></td>
                    <td><?php echo number_format($row['liters'], 2); ?> L</td>
                    <td>₹ <?php echo number_format($row['rate'], 2); ?></td>
                    <td class="total-amount">₹ <?php echo number_format($row['total_amount'], 2); ?></td>

                    <td>
                        <a class="delete-btn"
                           href="?delete=<?php echo $row['id']; ?>"
                           onclick="return confirm('Delete this entry?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../includes/footer.php"); ?>