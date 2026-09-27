<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

$id = $_GET['id'];

// Fetch existing data
$query = mysqli_query($conn, "
    SELECT customers.*, users.name, users.mobile, users.password 
    FROM customers 
    JOIN users ON customers.user_id = users.id
    WHERE customers.id='$id'
");

$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $rate = $_POST['rate'];
    $address = $_POST['address'];

    // Update users table
    mysqli_query($conn, "
        UPDATE users SET 
        name='$name',
        mobile='$mobile',
        password='$password'
        WHERE id='".$data['user_id']."'
    ");

    // Update customers table
    mysqli_query($conn, "
        UPDATE customers SET 
        custom_rate='$rate',
        address='$address'
        WHERE id='$id'
    ");

    echo "<script>alert('Customer Updated'); window.location='customers.php';</script>";
}
?>

<style>

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    min-height: 100vh;
}

.form-box {
    background: #ffffff !important; 
    padding: 30px;
    border-radius: 16px;
    max-width: 550px;
    margin: 40px auto; /* સેન્ટરમાં રાખવા માટે */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.form-box h3 {
    color: #1e293b; /* ટાઇટલ ડાર્ક કલરમાં */
    font-size: 1.5rem;
    margin-bottom: 20px;
    font-weight: 700;
}

input, textarea {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0 20px 0;
    background: #f8fafc; /* આછું ગ્રે બેકગ્રાઉન્ડ */
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #000000 !important; /* લખાણ એકદમ કાળા અક્ષરમાં */
    font-size: 1rem;
    font-weight: 500;
    outline: none;
    transition: all 0.3s ease;
}

input:focus, textarea:focus {
    border-color: #667eea;
    background: #ffffff;
    box-shadow: 0 0 8px rgba(102, 126, 234, 0.2);
}

/* અપડેટ બટન - પર્પલ ગ્રેડિયન્ટ */
button {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* લેબલ માટે જો ઉમેરવા હોય તો */
label {
    color: #475569;
    font-weight: 600;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 5px;
}
</style>

<div class="form-box">
    <h3>Edit Customer Details</h3>

    <form method="POST">
        <label>Customer Name</label>
        <input type="text" name="name" value="<?php echo $data['name']; ?>" required>
        
        <label>Mobile Number</label>
        <input type="text" name="mobile" value="<?php echo $data['mobile']; ?>" required>
        
        <label>Password</label>
        <input type="text" name="password" value="<?php echo $data['password']; ?>" required>
        
        <label>Rate per Liter (₹)</label>
        <input type="number" step="0.01" name="rate" value="<?php echo $data['custom_rate']; ?>" required>
        
        <label>Address</label>
        <textarea name="address" rows="3"><?php echo $data['address']; ?></textarea>

        <button name="update">Update Customer</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>