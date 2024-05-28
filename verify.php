<?php
require 'db.php';

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_code = ?");
    $stmt->execute([$verification_code]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET status = 1, verification_code = NULL WHERE verification_code = ?");
        $stmt->execute([$verification_code]);

        echo "Your email has been verified. You can now <a href='login.php'>login</a>.";
    } else {
        echo "Invalid verification code.";
    }
} else {
    echo "No verification code provided.";
}
?>