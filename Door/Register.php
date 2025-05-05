<?php
session_start();
require '../DB/DB_Connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingUser) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
                $stmt->execute([
                    'email' => $email,
                    'password' => $hashed_password
                ]);

                $_SESSION['success'] = "Registration successful! You can now log in.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Email already exists!";
            }
        } else {
            $error = "Passwords do not match!";
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
    <title>Register - Smart DrinkFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 30%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .password-wrapper {
            position: relative;
        }
        .form-control {
            padding-right: 35px; 
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-card text-center">
        <h3 class="text-light"> Create Your Account</h3>

        <p class="quote">"Ready to sip and save? Register now on DrinkFlow and start shopping for your favorite drinks!"</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label"> Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3 password-wrapper">
                <label for="password" class="form-label"> Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="fas fa-eye-slash eye-icon" id="togglePassword"></i>
            </div>
            <div class="mb-3 password-wrapper">
                <label for="confirm_password" class="form-label"> Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <i class="fas fa-eye-slash eye-icon" id="toggleConfirmPassword"></i>
            </div>
            <button type="submit" class="btn btn-success w-50"> Register</button>
            <a href="login.php" class="btn btn-secondary w-50 mt-2"> Go to Login</a>
            <a href="../index.php" class="btn btn-secondary w-50 mt-2">⬅ Back</a>
        </form>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const icon = document.getElementById('togglePassword');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        
        icon.classList.toggle('fa-eye-slash');
        icon.classList.toggle('fa-eye');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const confirmPasswordField = document.getElementById('confirm_password');
        const icon = document.getElementById('toggleConfirmPassword');
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        
        icon.classList.toggle('fa-eye-slash');
        icon.classList.toggle('fa-eye');
    });
</script>

</body>
</html>