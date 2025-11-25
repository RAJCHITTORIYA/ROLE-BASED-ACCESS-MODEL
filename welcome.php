<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome</title>
<link rel="stylesheet" href="/styles.css">
</head>
<body class="centered">
<div class="card" style="max-width:520px;">
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
<p class="muted">You are successfully logged in.</p>
<div class="actions">
    <a class="btn" href="user_dashboard.php">Go to Dashboard</a>
    <a class="btn secondary" href="logout.php">Logout</a>
  </div>
</div>
</body>
</html>
