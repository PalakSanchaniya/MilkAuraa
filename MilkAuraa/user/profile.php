<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/user_header.php");

/* USER DATA */
$user = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM users WHERE id='$user_id'
"));

/* CUSTOMER DATA */
$customer = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM customers WHERE user_id='$user_id'
"));

/* ADDRESS FIX */
$address = isset($customer['address']) ? $customer['address'] : 'Not Added';
?>

<style>

@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap');

body {
    background: linear-gradient(rgba(15, 23, 42, 0.55), rgba(15, 23, 42, 0.85)), url('../Images/profile_background.webp');
    background-size: cover; background-position: center; background-attachment: fixed;
    color: #ffffff; min-height: 100vh; font-family: 'Inter', sans-serif; margin: 0;
}


.profile-box{
    max-width:1000px;
    margin:auto;
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.08);
    backdrop-filter:blur(10px);
    border-radius:22px;
    padding:35px;
    box-shadow:0 20px 45px rgba(0,0,0,0.35);
}

.profile-top{
    display:flex;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
    padding-bottom:25px;
    border-bottom:1px solid rgba(255,255,255,0.08);
}

.avatar{
    width:90px;
    height:90px;
    border-radius:50%;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    color:#fff;
    font-size:34px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
}

.profile-top h2{
    color:#fff;
    font-size:30px;
    margin-bottom:6px;
}

.profile-top p{
    color:#cbd5e1;
    font-size:15px;
}

.section-title{
    color:#fff;
    font-size:22px;
    font-weight:700;
    margin:25px 0 18px;
    border-left:5px solid #6366f1;
    padding-left:12px;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.info-card{
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:18px;
    padding:22px;
    transition:0.3s;
}

.info-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 25px rgba(0,0,0,0.20);
}

.info-card span{
    display:block;
    color:#94a3b8;
    font-size:13px;
    margin-bottom:10px;
    text-transform:uppercase;
    letter-spacing:1px;
}

.info-card h4{
    color:#fff;
    font-size:19px;
    font-weight:700;
    word-break:break-word;
}

/* STATUS BOX */
.status-box{
    display:inline-block;
    padding:8px 16px;
    border-radius:30px;
    background:#16a34a;
    color:#fff;
    font-size:14px;
    font-weight:700;
}

.btn-box{
    text-align:center;
    margin-top:30px;
}

.back-btn{
    display:inline-block;
    padding:13px 28px;
    border-radius:12px;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    color:#fff;
    text-decoration:none;
    font-weight:700;
    transition:0.3s;
}

.back-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(99,102,241,0.35);
}

@media(max-width:768px){
    .page-wrapper{padding:18px;}
    .profile-box{padding:22px;}
    .profile-top{
        flex-direction:column;
        text-align:center;
    }
}
</style>

<div class="page-wrapper">

<div class="profile-box">

    <div class="profile-top">
        <div class="avatar">
            <?php echo strtoupper(substr($user['name'],0,1)); ?>
        </div>

        <div>
            <h2><?php echo $user['name']; ?></h2>
            <p>MilkAuraa Customer Profile</p>
        </div>
    </div>

    <div class="section-title">My Details</div>

    <div class="info-grid">

        <div class="info-card">
            <span>Full Name</span>
            <h4><?php echo $user['name']; ?></h4>
        </div>

        <div class="info-card">
            <span>Mobile Number</span>
            <h4><?php echo $user['mobile']; ?></h4>
        </div>

        <div class="info-card">
            <span>Milk Rate</span>
            <h4>₹ <?php echo number_format($customer['custom_rate'],2); ?>/L</h4>
        </div>

        <div class="info-card">
            <span>Address</span>
            <h4><?php echo $address; ?></h4>
        </div>

        <div class="info-card">
            <span>Status</span>
            <h4><span class="status-box">ACTIVE</span></h4>
        </div>

    </div>

    <div class="btn-box">
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

</div>
</div>

<?php include("../includes/user_footer.php"); ?>