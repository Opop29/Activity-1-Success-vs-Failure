<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require '../../DB/DB_Connection.php'; 

$user_id = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart DrinkFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/dashboard.css">
   
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="#">🍹 Smart DrinkFlow</a>
        <a href="../logout.php" class="btn btn-logout">🚪 Logout</a>
    </div>
</nav>

<div class="container dashboard-container">
    <div class="welcome-box">
        <h2>Welcome, <?php echo $username; ?>! 🎉</h2>
        <p>Smart DrinkFlow is here to optimize your beverage business.</p>
    </div>

    <input type="text" placeholder="Enter something..." class="form-control mx-auto">
</div>

</body>
</html>