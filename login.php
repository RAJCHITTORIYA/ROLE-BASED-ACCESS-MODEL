<?php
session_start();
include("config.php");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Fetch user by email

    $sql = $conn->prepare("SELECT * FROM users WHERE email='$email'");
    $sql->execute()          ;
    $user = $sql->fetch();


   if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
        $_SESSION['email'] = $user['email'];
          

    if ($user['role'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();                                                 // stops script execution (current execution)  after redirect.
}else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body class="centered">
    <div class="card">
        <?php $showWelcomeBack = isset($_GET['logged_out']) && $_GET['logged_out'] == '1'; ?>
        <h2><?php echo $showWelcomeBack ? 'Welcome back' : 'Sign in'; ?></h2>
        <p class="muted">Sign in to continue</p>

        <?php if (isset($error)) echo "<p class=\"error\">$error</p>"; ?>

        <form method="post">
            <div class="field">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="field">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="actions">
                <button type="submit" name="login" class="btn">Login</button>
                <a class="btn google-btn" href="google_login.php">
                    <img class="google-logo" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="">
                    Login with Google
                </a>
            </div>
        </form>

        <p class="muted" style="margin-top:14px;">Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
