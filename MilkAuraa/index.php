<?php
session_start();
include("config/db.php");

$error = ""; 

if (isset($_POST['login'])) {
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE mobile='$mobile'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // પાસવર્ડ ચેક (જો તમે હેશ વાપરતા હોવ તો password_verify વાપરવું)
        if ($password == $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // રોલ મુજબ રીડાયરેક્ટ
            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
                exit();
            } else {
                header("Location: user/dashboard.php");
                exit();
            }
        } else {
            $error = "Invalid Mobile Number or Password!";
        }
    } else {
        $error = "No account found with this number!";
    }
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MilkAura - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            height: 100vh; width: 100%;
            display: flex; align-items: center; justify-content: center;
            background: url('Images/login.webp') no-repeat center center fixed;
            background-size: cover; position: relative;
        }

        body::before {
            content: ""; position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.45); z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            padding: 45px 35px; width: 90%; max-width: 380px;
            border-radius: 25px; border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            text-align: center; position: relative; z-index: 10;
        }

        .brand-name { font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: 5px; }
        .brand-name span { color: #4cc9f0; }

        .subtitle {
            color: #e2e8f0; font-size: 0.85rem; font-weight: 500;
            margin-bottom: 30px; letter-spacing: 0.8px;
            font-style: italic; opacity: 0.9; line-height: 1.5;
        }

        /* Error Message Styling */
        .error-box {
            background: rgba(255, 0, 0, 0.2);
            color: #ffcccc; border: 1px solid rgba(255, 0, 0, 0.3);
            padding: 10px; border-radius: 10px;
            margin-bottom: 20px; font-size: 0.9rem;
        }

        .input-group { position: relative; margin-bottom: 20px; }

        input {
            width: 100%; padding: 15px;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px; color: #fff;
            font-size: 1rem; outline: none; transition: 0.3s;
        }

        input:focus { border-color: #4cc9f0; background: rgba(0, 0, 0, 0.75); }

        .toggle-password {
            position: absolute; right: 15px; top: 50%;
            transform: translateY(-50%); cursor: pointer;
            color: #94a3b8; z-index: 11;
        }

        button {
            width: 100%; padding: 15px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white; font-size: 1.1rem; font-weight: 700;
            cursor: pointer; transition: 0.3s; text-transform: uppercase;
        }

        button:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(67, 97, 238, 0.4); }

        .footer-text { margin-top: 25px; font-size: 0.8rem; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="login-card">
        <h1 class="brand-name">Milk<span>Auraa</span></h1>
        <p class="subtitle">Purity in Every Drop,<br>Precision in Every Record.</p>

        <?php if ($error != ""): ?>
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <input type="text" name="mobile" placeholder="Mobile Number" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="fas fa-eye toggle-password" id="eyeIcon" onclick="togglePassword()"></i>
            </div>

            <button name="login" type="submit">LOGIN NOW</button>
        </form>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const icon = document.getElementById("eyeIcon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>