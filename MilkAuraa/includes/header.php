<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database connection include
include("../config/db.php");

// Extra Requests 
$pending_count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='pending'");
$pending_data = mysqli_fetch_assoc($pending_count_res);
$total_pending = $pending_data['total'] ?? 0;

// Announcements
$ann_count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM announcements WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$ann_data = mysqli_fetch_assoc($ann_count_res);
$total_ann = $ann_data['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>MilkAuraa</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(15, 23, 42, 0.87);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            color: #66b0bd;
            padding: 2rem 0;
            border-right: 1px solid rgba(192, 151, 151, 0.12);
            position: sticky;
            top: 0;
            height: 100vh;
        }

        /* LOGO */
        .logo {
            padding: 0 2rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
            text-align: center;
        }

        .logo img {
            width: 180px;
            height: auto;
        }

        /* Navigation */
        .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            background: transparent;
        }

        .nav-link:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.15) 0%, transparent 100%);
            color: #ecd1d1;
            padding-left: 2.5rem;
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.25) 0%, transparent 100%);
            color: #a78bfa;
            border-left: 4px solid #667eea;
            font-weight: 600;
        }

        /* Notification Badge Style */
        .badge {
            background: #ef4444; /* Red color */
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 50px;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
            margin-left: 8px;
        }

        /* Blue Badge for Announcements */
        .badge-info {
            background: #38bdf8;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            background: #0b2136;
        }

        .header {
            background: #9098d3;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="sidebar">
        <div class="logo">
            <img src="../Images/Logo.png" alt="MilkAura Logo">
        </div>

        <a href="dashboard.php" class="nav-link">📊 Dashboard</a>
        <a href="customers.php" class="nav-link">👥 Customers</a>
        <a href="milk_entry.php" class="nav-link">🥛 Milk Entry</a>
        <a href="milk_history.php" class="nav-link">🗓️ Milk History</a>
        <a href="reports.php" class="nav-link">📝 Reports</a>
        <a href="billing.php" class="nav-link">📃 Billing</a>
        <a href="announcements.php" class="nav-link">🔔 Announcement</a>

        <a href="orders.php" class="nav-link">
            <span>➕ Extra Requests</span>
            <?php if($total_pending > 0): ?>
                <span class="badge"><?php echo $total_pending; ?></span>
            <?php endif; ?>
        </a>

        <a href="../logout.php" class="nav-link" style="color:#f87171;">🔙 Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>MilkAuraa Admin Panel</h2>
        </div>