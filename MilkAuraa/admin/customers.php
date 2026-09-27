<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

$query = mysqli_query($conn, "
    SELECT customers.*, users.name, users.mobile 
    FROM customers 
    JOIN users ON customers.user_id = users.id
");
?>

<style>
/* Table Container - સફેદ બેકગ્રાઉન્ડ રાખ્યું છે જેથી અક્ષરો ડાર્ક દેખાય */
.table-box {
    background: #ffffff !important; 
    padding: 25px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-top: 20px;
}

/* Professional Add Button */
.add-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 12px 24px;
    background: #6b46c1; /* Rich Purple color */
    color: white !important;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
}

.add-btn:hover {
    background: #553c9a;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(107, 70, 193, 0.3);
}

/* Table Text Settings */
table {
    width: 100%;
    border-collapse: collapse;
    color: #1e293b; /* અક્ષરોનો કલર ડાર્ક નેવી બ્લુ રાખ્યો છે */
}

th {
    text-align: left;
    padding: 15px;
    border-bottom: 2px solid #f1f5f9;
    color: #64748b; /* Header color thodo aacho dark */
    text-transform: uppercase;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 1px;
}

td {
    padding: 15px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b !important; /* Customer Name ane biji details ekdam dark dekhase */
    font-weight: 500;
}

/* Row hover effect */
tr:hover {
    background: #f8fafc; 
}

/* Action links (Edit/Delete) mate dark color */
td a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

td a:hover {
    text-decoration: underline;
}
</style>

<div class="table-box">

    <a href="add_customer.php" class="add-btn">+ Add Customer</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Rate</th>
                <th>Action</th>
            </tr>
        </thead>

       <tbody>
    <?php $i = 1; ?>
    <?php while($row = mysqli_fetch_assoc($query)) { ?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['mobile']; ?></td>
        <td><?php echo $row['custom_rate']; ?></td>
        <td>
            <a href="edit_customer.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="delete_customer.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</tbody>
    </table>

</div>

<?php include("../includes/footer.php"); ?>