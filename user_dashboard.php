<?php
include('auth.php');


if (!isUser()) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<div class="container">
    <div class="actions" style="justify-content: space-between;">
        <h2 style="margin:0;">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
        <a class="btn secondary" href="logout.php">Logout</a>
    </div>
    <div class="card" style="max-width: 640px; margin-top: 16px;">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
    </div>
</div>
</body>
</html>
