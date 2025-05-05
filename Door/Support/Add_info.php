<?php
session_start();
require '../../DB/DB_Connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (!empty($username) && !empty($phone) && !empty($address)) {
 
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username = :username OR phone = :phone) AND id != :id");
        $check_stmt->execute([
            'username' => $username,
            'phone' => $phone,
            'id' => $_SESSION['user_id']
        ]);
        $count = $check_stmt->fetchColumn();

        if ($count > 0) {
            $error = "This username or phone number is already in use. Please choose another.";
        } else {
            // Proceed with updating the user's profile
            $stmt = $pdo->prepare("UPDATE users SET username = :username, phone = :phone, address = :address WHERE id = :id");
            $stmt->execute([
                'username' => $username,
                'phone' => $phone,
                'address' => $address,
                'id' => $_SESSION['user_id']
            ]);

            header("Location: ./page/Dashboard.php");
            exit;
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
    <title>Complete Profile - Smart DrinkFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/take_info.css"> 
</head>
<body>
<div class="container">
    <div class="info-card text-center">
        <h3 class="text-light">📝 Complete Your Profile</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">👤 Full Name</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">📞 Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">🏠 Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn btn-success w-100">✅ Save & Proceed</button>
        </form>
    </div>
</div>

<script>
    document.querySelector("form").addEventListener("submit", function() {
        document.querySelector("button[type='submit']").disabled = true;
    });
</script>

</body>
</html>