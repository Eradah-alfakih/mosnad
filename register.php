<?php
require 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure Composer's autoload is included

function sendVerificationEmail($email, $verification_code) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'eradahalfakeh@gmail.com'; // SMTP username
        $mail->Password   = 'aldzvdhwabfkuwwo'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('eradahalfakeh@gmail.com', 'Mosnad');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body    = "Please click the link below to verify your email:<br><a href='http://localhost/mosnad/project/verify.php?code=$verification_code'>Verify Email</a>";

        $mail->send();
        echo 'A verification email has been sent to your email address. Please check your email to activate your account.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $verification_code = bin2hex(random_bytes(16));

    // Validate Yemeni phone number
    if (!preg_match('/^((\\+|00)967|0)?7[0-9]{8}$/', $phone)) {
        $error = "Invalid Yemeni phone number!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, phone, verification_code) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $password, $email, $phone, $verification_code]);

            sendVerificationEmail($email, $verification_code);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation
                $error = "Username, email, or phone already exists!";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Register</title>
</head>

<body>
    <div class="login">
        <h1>Register</h1>
        <form method="post">

            <input type="text" name="username" placeholder="Username" required><br>

            <input type="email" name="email" placeholder="email" required><br>
            <input type="password" name="password" placeholder="password" required><br>
            <input type="phone" name="phone" placeholder="phone" required><br>

            <button type="submit" class="btn btn-primary btn-block btn-large"> Register</button>

        </form>
    </div>

    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>

</html>