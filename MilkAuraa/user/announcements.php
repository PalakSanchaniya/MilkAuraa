<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/user_header.php");

$user_id = $_SESSION['user_id'] ?? 0;

if($user_id > 0) {
    mysqli_query($conn, "UPDATE customers SET last_announcement_view = NOW() WHERE user_id = '$user_id'");
}

$q = mysqli_query($conn,"SELECT * FROM announcements ORDER BY id DESC");
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap');

body {
    background: linear-gradient(rgba(15, 23, 42, 0.45), rgba(15, 23, 42, 0.75)), url('../Images/background.webp');
    background-size: cover; background-attachment: fixed; color: #ffffff; font-family: 'Inter', sans-serif;
}

.page-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 40px;
    font-weight: 700;
    color: #ffffff;
    margin: 30px 0;
    position: relative;
    display: inline-block;
    letter-spacing: -1px;
}

.page-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 60%;
    height: 4px;
    background: linear-gradient(90deg, #38bdf8, transparent);
    border-radius: 10px;
}


.card {
    background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(10px);
    border-radius: 18px; padding: 25px; border: 1px solid rgba(56, 189, 248, 0.2); transition: .3s;
}
.card:hover { transform: translateY(-5px); border-color: #38bdf8; }
.card h3 { color: #38bdf8; margin-bottom: 10px; }
.card p { color: #e5e7eb; line-height: 1.6; }
.date { color: #94a3b8; font-size: 13px; margin-top: 15px; }
</style>

<div class="container" style="padding: 20px;">
    <h1 class="page-title">Announcements</h1>

    <div class="wrapper">
    <?php if(mysqli_num_rows($q) > 0){ ?>
        <?php while($row=mysqli_fetch_assoc($q)){ ?>
            <div class="card">
                <h3>MilkAuraa Notice</h3>
                <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                <div class="date">
                    📅 Posted on: <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div style="text-align:center; padding: 50px; opacity: 0.5;">No announcements yet.</div>
    <?php } ?>
    </div>
</div>

<?php include("../includes/user_footer.php"); ?>