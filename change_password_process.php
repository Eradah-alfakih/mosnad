<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the user's current password from the database
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    // Verify the old password
    if ($user && password_verify($old_password, $user['password'])) {
        // Validate the new password
        if ($new_password != $confirm_password) {
            $error = "New password and confirm password do not match!";
        } elseif (strlen($new_password) < 8 || !preg_match('/[a-zA-Z]/', $new_password) || !preg_match('/\d/', $new_password)) {
            $error = "New password must be at least 8 characters long and contain at least one letter and one number!";
        } else {
            // Hash and update the new password in the database
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);

            // Redirect to a success page or display a success message
            header("Location: password_changed.php");
            exit();
        }
    } else {
        $error = "Incorrect old password!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
</head>

<body>
    <h2>Change Password</h2>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <a href="change_password.php">Go back</a>
</body>

</html>