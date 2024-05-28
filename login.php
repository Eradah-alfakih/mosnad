<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the input is an email
    if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    }  
    $stmt->execute([$username_or_email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] == 1) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            header('Location: index.php');
        } else {
            $error = "Your account is not activated. Please check your email for the activation link.";
        }
    } else {
        $error = "Invalid username/email or password!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Login</title>
</head>

<body>
    <div class="login">
        <h1>Login</h1>
        <form method="post">
            <input type="email" name="email" placeholder="email" required="required" />
            <input type="password" name="password" placeholder="Password" required="required" />
            <button type="submit" class="btn btn-primary btn-block btn-large"> Login</button>
        </form>
    </div>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>

</html>