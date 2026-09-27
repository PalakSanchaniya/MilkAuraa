<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../config/db.php");

// લોગિન થયેલ યુઝરની આઈડી મેળવો
$user_id = $_SESSION['user_id'] ?? 0;

// નવી જાહેરાતો ગણવા માટેનું લોજિક
$new_ann_count = 0;
if($user_id > 0) {
    // આ ક્વેરી ચેક કરશે કે છેલ્લે જોયા પછી કેટલી નવી જાહેરાતો આવી છે
    $count_q = mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM announcements 
        WHERE created_at > (SELECT last_announcement_view FROM customers WHERE user_id = '$user_id')
    ");
    $count_data = mysqli_fetch_assoc($count_q);
    $new_ann_count = $count_data['total'] ?? 0;
}

// હાલનું પેજ કયું છે તે મેળવો (મેનુમાં Active ક્લાસ બતાવવા માટે)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MilkAuraa User Panel</title>

<style>
/* Import Unique & Professional Font: Outfit */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Outfit', sans-serif;
}

body {
    background: #0f172a;
    color: #f8fafc;
    line-height: 1.6;
}

/* --- OFFICIAL NAVBAR --- */
.navbar {
    width: 100%;
    padding: 15px 60px;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.logo {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo img {
    height: 55px;
    filter: drop-shadow(0 0 8px rgba(56, 189, 248, 0.3));
}

/* --- PROFESSIONAL MENU --- */
.menu {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.03);
    padding: 6px;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.menu a {
    color: #94a3b8;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 15px;
    font-weight: 500;
    display: flex;
    align-items: center;
    position: relative;
}

.menu a:hover {
    background: rgba(56, 189, 248, 0.1);
    color: #38bdf8;
}

/* Active Page Highlight */
.menu a.active {
    background: #38bdf8;
    color: #0f172a;
    font-weight: 600;
}

/* --- NOTIFICATION BADGE --- */
.notif-badge {
    background: #ef4444;
    color: white;
    font-size: 11px;
    font-weight: 700;
    height: 20px;
    min-width: 20px;
    padding: 0 6px;
    border-radius: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 8px;
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    animation: pulse-red 2s infinite;
}

@keyframes pulse-red {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

.logout {
    background: #ef4444 !important;
    color: white !important;
    margin-left: 10px;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.logout:hover {
    background: #dc2626 !important;
    transform: translateY(-2px);
}

/* --- PAGE CONTENT CONTAINER --- */
.container {
    padding: 40px 60px;
    max-width: 1400px;
    margin: 0 auto;
}

/* --- MOBILE RESPONSIVE --- */
@media (max-width: 1024px) {
    .navbar { padding: 15px 30px; }
}

@media (max-width: 900px) {
    .navbar { flex-direction: column; gap: 20px; padding: 20px; }
    .menu { width: 100%; justify-content: center; flex-wrap: wrap; }
    .container { padding: 30px 20px; }
}
</style>
</head>

<body>

<div class="navbar">

    <div class="logo">
        <img src="../Images/logo.png" alt="MilkAuraa Logo">
    </div>

    <div class="menu">
        <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">📊 Dashboard</a>
        
        <a href="milk_history.php" class="<?php echo ($current_page == 'milk_history.php') ? 'active' : ''; ?>">🥛 Milk History</a>
        
        <a href="extra_request.php" class="<?php echo ($current_page == 'extra_request.php') ? 'active' : ''; ?>">➕ Extra Request</a>
        
        <a href="announcements.php" class="<?php echo ($current_page == 'announcements.php') ? 'active' : ''; ?>">
            🔔 Announcements
            <?php if($new_ann_count > 0): ?>
                <span class="notif-badge"><?php echo $new_ann_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="payment_history.php" class="<?php echo ($current_page == 'payment_history.php') ? 'active' : ''; ?>">💳 Payment History</a>

        <a href="download_bill.php" class="<?php echo ($current_page == 'download_bill.php') ? 'active' : ''; ?>">🖨️ Bill</a>
        
        <a href="profile.php" class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">👤 Profile</a>
        
        <a href="../logout.php" class="logout">Logout</a>
    </div>

</div>

<div class="container">