<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

// Fetch customers
$customers = mysqli_query($conn, "
    SELECT customers.id, users.name 
    FROM customers 
    JOIN users ON customers.user_id = users.id
");

if (isset($_POST['submit'])) {

    $customer_id = $_POST['customer_id'];
    $date = $_POST['date'];
    $shift = $_POST['shift'];
    $liters = $_POST['liters'];

    // Get customer rate
    $rate_query = mysqli_query($conn, "SELECT custom_rate FROM customers WHERE id='$customer_id'");
    $rate_data = mysqli_fetch_assoc($rate_query);
    $rate = $rate_data['custom_rate'];

    // Calculate total
    $total = $liters * $rate;

    // Insert
    mysqli_query($conn, "
        INSERT INTO milk_entries (customer_id, date, shift, liters, rate, total_amount)
        VALUES ('$customer_id', '$date', '$shift', '$liters', '$rate', '$total')
    ");

    echo "<script>alert('Milk Entry Added');</script>";
}
?>

<style>
.page-wrapper {
    padding: 20px;
}

/* 🔥 SAME BOX STYLE (ALL PAGES JEVI) */
.box {
    background: #ffffff !important; 
    padding: 30px;
    border-radius: 16px;
    max-width: 550px;
    margin: 40px auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

/* SAME HEADING STYLE */
.box h3 {
    color: #1e293b;
    font-size: 1.5rem;
    margin-bottom: 25px;
    font-weight: 700;
    border-left: 5px solid #16a34a;
    padding-left: 15px;
}

/* INPUTS */
input, select, textarea {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0 20px 0;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #000000 !important;
    font-size: 1rem;
    font-weight: 500;
    outline: none;
    transition: 0.3s;
}

input:focus, select:focus, textarea:focus {
    border-color: #16a34a;
    background: #ffffff;
    box-shadow: 0 0 8px rgba(22, 163, 74, 0.2);
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    background: #16a34a;
    color: white !important;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
}

/* LABEL */
label {
    color: #475569;
    font-weight: 600;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 5px;
}
</style>

<div class="page-wrapper">
    <div class="box">
        <h3>Milk Entry</h3>

        <form method="POST">
            <label>Select Customer</label>
            <select name="customer_id" required>
                <option value="">Choose a customer...</option>
                <?php while($c = mysqli_fetch_assoc($customers)) { ?>
                    <option value="<?php echo $c['id']; ?>">
                        <?php echo $c['name']; ?>
                    </option>
                <?php } ?>
            </select>

            <label>Date</label>
            <input type="date" name="date" required>

            <label>Shift</label>
            <select name="shift" required>
                <option value="morning">Morning</option>
                <option value="evening">Evening</option>
            </select>

            <label>Liters</label>
            <input type="number" step="0.01" name="liters" placeholder="Enter liters" required>

            <button name="submit">Save Entry</button>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>