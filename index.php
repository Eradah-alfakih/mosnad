<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role_id = $_SESSION['role_id'];

switch ($role_id) {
    case 1:
        header('Location: admin.php');
        break;
    case 2:
        header('Location: user.php');
        break;
    case 3:
        header('Location: guest.php');
        break;
    default:
        header('Location: login.php');
        break;
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Home</title>
</head>

<body>
    <h2>Welcome</h2>
    <p>You are logged in.</p>
    <a href="logout.php">Logout</a>
</body>

</html>