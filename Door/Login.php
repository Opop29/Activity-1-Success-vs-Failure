<?php
session_start();
require '../DB/DB_Connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['banned'] == 1) {
                $error = "⛔ You have been banned from accessing this platform.";
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                if (empty($user['username']) || empty($user['phone']) || empty($user['address'])) {
                    header("Location: ./Support/add_info.php");
                    exit;
                } else {
                    $otp = rand(100000, 999999);
                    $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

                    $otp_stmt = $pdo->prepare("UPDATE users SET otp_code = :otp, otp_expires = :expiry WHERE id = :id");
                    $otp_stmt->execute([
                        'otp' => $otp,
                        'expiry' => $expiry,
                        'id' => $user['id']
                    ]);

                    $_SESSION['otp_sent'] = true;

                    header("Location: ./Support/verify_otp.php");
                    exit;
                }
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart DrinkFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/login.css"> 
    <style>
        .alert-danger {
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            margin: 20px auto;
            width: 80%;
        }
        .login-card {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            margin-top: 50px;
        }
        .logo {
            width: 120px;
            margin-bottom: 15px;
        }
        body {
            background-color: #111;
            color: white;
        }
        .quote {
            font-style: italic;
            color: #ccc;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-card text-center">
        <img src="../CSS/Media/logo.webp" alt="DrinkFlow Logo" class="logo">
        <h3 class="text-light">🔒 Login to DrinkFlow</h3>
        <p class="quote">"Thirsty for the best deals? Log in to DrinkFlow and grab your favorite drinks now!"</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label"> Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"> Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-50"> Login</button>
            <a href="register.php" class="btn btn-success w-50 mt-2"> Register</a>
            <a href="../index.php" class="btn btn-secondary w-50 mt-2"> Back</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.querySelector('.alert-danger');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.opacity = '0';
                alertBox.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alertBox.remove(), 500);
            }, 5000);
        }
    });
</script>

</body>
</html>