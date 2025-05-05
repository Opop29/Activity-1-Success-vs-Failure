<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart DrinkFlow - Refreshing Drinks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/index.css"> 
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <Style></Style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="./CSS/Media/Logo.webp" alt="Smart DrinkFlow Logo">
            Smart DrinkFlow
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav position-relative">
                <li class="nav-item"><a class="nav-link active" href="#" onclick="moveIndicator(this)">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#products" onclick="moveIndicator(this)">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#footer" onclick="moveIndicator(this)">About</a></li>
            </ul>
            <div class="nav-indicator"></div> 
        </div>
        <div class="d-flex">
            <a class="nav-link btn btn-primary text-white" href="./Door/Login.php">Login</a>
            <a class="nav-link btn btn-primary text-white" href="./Door/Register.php">Register</a>
        </div>
    </div>
</nav>

<header class="hero-section text-center text-white">
    <div class="container">
        <h1>Refresh Your Thirst with Smart DrinkFlow</h1>
        <p>Premium beverages to keep you energized.</p>
        <div class="search-container">
            <input type="text" id="search-bar" placeholder="Search for drinks...">
            <button type="submit" id="search-btn">🔍</button>
        </div>
    </div>
</header>



    <section id="products" class="container my-5">
        <h2 class="text-center mb-4">Our Top Drinks</h2>
        <div class="row">
            <?php
            $drinks = [
                ["name" => "Coca-Cola", "image" => "coke.jpg"],
                ["name" => "Pepsi", "image" => "pepsi.jpg"],
                ["name" => "Sprite", "image" => "sprite.jpg"],
                ["name" => "Fanta", "image" => "fanta.jpg"],
                ["name" => "Red Bull", "image" => "redbull.jpg"],
            ];
            foreach ($drinks as $drink) {
                echo "<div class='col-md-4 text-center'>
                        <div class='card'>
                            <img src='images/{$drink['image']}' class='card-img-top' alt='{$drink['name']}'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$drink['name']}</h5>
                                <a href='#' class='btn btn-primary'>Order Now</a>
                            </div>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </section>
    <link rel="stylesheet" href="./CSS/footer.css"> 

<footer id="footer">
    <div class="container">
        <h3>Smart DrinkFlow</h3>
        <p>"Quench your thirst, fuel your passion."</p>

        <div class="footer-sections">
            <div class="footer-section">
                <h5>About Us</h5>
                <p>Delivering the finest beverages with a seamless supply chain experience. Stay refreshed with every sip.</p>
            </div>

            <div class="footer-section">
                <h5>Our Team</h5>
                <ul>
                    <li>👤 Joshua T. Quidit </li>
                    <li>Lead Developer/Backend Developer</li>
                    <li>👤 Jacob Jay Estahan</li>
                    <li>UI/UX Designer/Papers</li>
                </ul>
            </div>

            <div class="footer-section">
                <h5>Contact Us</h5>
                <p>📧 <a href="mailto:Sankanan@nbsc.edu.ph">info@smartdrinkflow.com</a></p>
                <p>📞 +63 912 345 6789 / 📞 +63 912 345 6789</p>  
                <p>📍 Zone 2/4 Sankanan Manolo Fortich Bukidnon, PH</p>
            </div>
        </div>

        <div class="footer-social">
            <a href="https://www.facebook.com/reel/1953319178452997" class="social-link" target="_blank">
                <img src="./CSS/Media/fb_logo.png" alt="Facebook">
            </a>

            <a href="#" class="social-link">
                <img src="./CSS/Media/tiktok.png" alt="TikTok">
            </a>
            <a href="#" class="social-link">
                <img src="./CSS/Media/instagram.png" alt="Instagram">
            </a>
        </div>

        <hr class="footer-divider">
        <p>&copy; 2025 Smart DrinkFlow. All rights reserved.</p>
    </div>
</footer>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>