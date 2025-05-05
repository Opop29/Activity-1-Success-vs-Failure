<?php
session_start();
require '../../DB/DB_Connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Door/login.php");
    exit;
}

$error = "";
$showModal = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = trim($_POST['otp_combined']);

    if (!empty($otp)) {
        $stmt = $pdo->prepare("SELECT otp_code, otp_expires FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $otp = preg_replace('/\D/', '', $otp);
            $stored_otp = preg_replace('/\D/', '', $user['otp_code']);

            if ($otp == $stored_otp && strtotime($user['otp_expires']) > time()) {
                $clear_stmt = $pdo->prepare("UPDATE users SET otp_code = NULL, otp_expires = NULL WHERE id = :id");
                $clear_stmt->execute(['id' => $_SESSION['user_id']]);

                header("Location: ../page/Dashboard.php");
                exit;
            } else {
                $error = "Invalid or expired OTP!";
                $showModal = true; 
            }
        }
    } else {
        $error = "Please enter the OTP!";
        $showModal = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Smart DrinkFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../CSS/OTP.css">
</head>
<body>
<div class="container">
    <div class="login-card text-center">
        <h3 class="text-light">🔐 Enter OTP</h3>
        <p class="quote">"We've sent a 6-digit code to your email or screen. Enter it below to continue."</p>

        <form method="POST" id="otpForm">
            <div class="otp-inputs d-flex justify-content-center gap-2 mb-3">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" class="form-control text-center otp-box" maxlength="1" name="otp[]" required>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="otp_combined" id="otp_combined">
            <button type="submit" class="btn btn-primary w-100">✅ Verify & Continue</button>
            <a href="../../index.php" class="btn btn-secondary w-50 mt-2">⬅ Back</a>
        </form>
    </div>
</div>

<div class="modal fade" id="otpWarningModal" tabindex="-1" aria-labelledby="otpWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="otpWarningModalLabel">⚠️ OTP Expired</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p><?php echo $error; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?php if ($showModal): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var otpWarningModal = new bootstrap.Modal(document.getElementById("otpWarningModal"));
        otpWarningModal.show();
    });
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-box');
    const hiddenInput = document.getElementById('otp_combined');
    const form = document.getElementById('otpForm');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        if (index === 0) {
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
                const chars = pastedData.split('');
                chars.forEach((char, i) => {
                    if (i < inputs.length) {
                        inputs[i].value = char;
                    }
                });
                if (chars.length > 0) {
                    inputs[Math.min(chars.length, inputs.length) - 1].focus();
                }
            });
        }
    });

    form.addEventListener('submit', (e) => {
        let combined = '';
        inputs.forEach(input => combined += input.value);
        hiddenInput.value = combined;
    });
});
</script>

<!-- ✅ OTP Display Box (REMOVE IN PRODUCTION) -->
<?php
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT otp_code FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && !empty($user['otp_code'])) {
        echo "<div style='position:fixed; bottom:10px; right:10px; background:#333; color:#fff; padding:10px; border-radius:5px; z-index:9999; font-size:14px;'>OTP: " . htmlspecialchars($user['otp_code']) . "</div>";
    }
}
?>

</body>
</html>
