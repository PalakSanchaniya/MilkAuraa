<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $rate = $_POST['rate'];
    $address = $_POST['address'];

    // 1. Insert into users table
    $user_query = "INSERT INTO users (name, mobile, password, role)
                   VALUES ('$name', '$mobile', '$password', 'customer')";
    mysqli_query($conn, $user_query);

    $user_id = mysqli_insert_id($conn);

    // 2. Insert into customers table
    $customer_query = "INSERT INTO customers (user_id, address, custom_rate)
                       VALUES ('$user_id', '$address', '$rate')";
    mysqli_query($conn, $customer_query);

    echo "<script>alert('Customer Added Successfully'); window.location='customers.php';</script>";
}
?>

<style>
/* Form Container */
.form-box {
    background: #ffffff; /* ચોખ્ખું સફેદ બેકગ્રાઉન્ડ */
    padding: 30px;
    border-radius: 20px;
    max-width: 500px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
}

/* Label Styling - હવે ડાર્ક કલરમાં */
.form-box label {
    color: #1e293b; /* Dark Blue-Black color */
    font-size: 0.9rem;
    margin-bottom: 5px;
    display: block;
    font-weight: 600;
}

/* Input & Textarea Fields - અક્ષરો કાળા દેખાય એ માટે */
input, textarea {
    width: 100%;
    padding: 12px 16px;
    margin: 10px 0 20px 0;
    background: #f8fafc; /* આછું ગ્રે બેકગ્રાઉન્ડ */
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    color: #1a1a2e; /* Black/Dark Font Color */
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
    outline: none;
    transition: all 0.3s ease;
}

/* Input Focus Effect */
input:focus, textarea:focus {
    background: #ffffff;
    border-color: #667eea;
    box-shadow: 0 0 10px rgba(102, 126, 234, 0.2);
}

/* Placeholder Color - થોડો ડાર્ક ગ્રે */
input::placeholder, textarea::placeholder {
    color: #94a3b8;
}

/* Form Submit Button - પર્પલ કલર જ રાખ્યો છે જે સફેદ પર મસ્ત લાગશે */
button {
    width: 100%;
    padding: 14px;
    background: #6b46c1; /* Solid Professional Purple */
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 1px;
    text-transform: uppercase;
}

button:hover {
    background: #553c9a;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 70, 193, 0.3);
}

button:active {
    transform: translateY(0);
}

/* Textarea height fix */
textarea {
    resize: vertical;
    min-height: 100px;
}
</style>
<div class="form-box">
    <h3>Add Customer</h3>

    <form method="POST">
        <input type="text" name="name" placeholder="Customer Name" required>
        <input type="text" name="mobile" placeholder="Mobile Number" required>
        <input type="text" name="password" placeholder="Password" required>
        <input type="number" step="0.01" name="rate" placeholder="Rate per Liter" required>
        <textarea name="address" placeholder="Address"></textarea>

        <button name="submit">Add Customer</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>