<?php
include("../config/db.php");

$id = $_GET['id'];

// Get user_id first
$result = mysqli_query($conn, "SELECT user_id FROM customers WHERE id='$id'");
$data = mysqli_fetch_assoc($result);

$user_id = $data['user_id'];

// Delete from customers
mysqli_query($conn, "DELETE FROM customers WHERE id='$id'");

// Delete from users
mysqli_query($conn, "DELETE FROM users WHERE id='$user_id'");

header("Location: customers.php");
?>