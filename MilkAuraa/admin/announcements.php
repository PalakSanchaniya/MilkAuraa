<?php
include("../config/db.php");
include("../includes/auth.php");


include("../includes/header.php");

date_default_timezone_set('Asia/Kolkata');

// Add announcement
if(isset($_POST['add'])){
    $msg = mysqli_real_escape_string($conn, $_POST['msg']);
    $date = date('Y-m-d H:i:s');

    mysqli_query($conn,"INSERT INTO announcements (message, created_at) VALUES ('$msg', '$date')");
    echo "<script>alert('Announcement Posted!'); window.location.href='announcements.php';</script>";
}

// Delete announcement
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM announcements WHERE id='$id'");
    echo "<script>alert('Deleted!'); window.location.href='announcements.php';</script>";
}
?>

<style>
.page-wrapper {
    padding: 20px;
    max-width: 1000px;
    margin: auto;
}

.box { 
    background:#fff; 
    padding: 30px; 
    border-radius: 15px; 
    margin-bottom: 30px; 
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

h3 {
    color: #1e293b;
    font-weight: 700;
    margin-bottom: 20px;
    border-left: 5px solid #4f46e5;
    padding-left: 15px;
}

textarea {
    width: 100%;
    height: 120px;
    padding: 15px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 1rem;
    color: #000 !important;
    resize: none;
}

button {
    background: #4f46e5;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.announcement-card {
    background: #f1f5f9;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    border-left: 4px solid #16a34a;
    position: relative;
}

.delete-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    color: red;
    text-decoration: none;
    font-weight: bold;
}
</style>

<div class="page-wrapper">

<div class="box">
    <h3>Post New Announcement</h3>

    <form method="POST">
        <textarea name="msg" placeholder="Write message..." required></textarea>
        <br><br>
        <button name="add">Post</button>
    </form>
</div>

<div class="box">
    <h3>All Announcements</h3>

    <?php
    $q = mysqli_query($conn,"SELECT * FROM announcements ORDER BY id DESC");

    if(mysqli_num_rows($q)>0){
        while($r=mysqli_fetch_assoc($q)){
    ?>
        <div class="announcement-card">
            
            <!-- DELETE BUTTON -->
            <a class="delete-btn" 
               href="?delete=<?php echo $r['id']; ?>" 
               onclick="return confirm('Delete this announcement?')">
               ✖
            </a>

            <p><?php echo nl2br(htmlspecialchars($r['message'])); ?></p>

            <span>
                <?php echo date('d M Y, h:i A', strtotime($r['created_at'])); ?>
            </span>
        </div>
    <?php
        }
    } else {
        echo "No announcements";
    }
    ?>
</div>

</div>

<?php include("../includes/footer.php"); ?>