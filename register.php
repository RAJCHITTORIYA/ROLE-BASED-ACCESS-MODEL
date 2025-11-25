<?php
session_start();
include("config.php");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ;
    
    // Hash password before saving
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data
    $sql = "INSERT INTO users (username, email, password,role) VALUES (?, ?, ?,?)"; 
    $stmt = $conn->prepare($sql);                                                  
                                                                              
    try {
        $stmt->execute([$username, $email, $hashedPassword , $role]);
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body class="centered">
    <div class="card">
        <h2>Create your account</h2>
        <p class="muted">It takes less than a minute</p>

        <?php 
        if (isset($_SESSION['error'])) { echo "<p class=\"error\">".$_SESSION['error']."</p>"; unset($_SESSION['error']); }
        ?>

        <form method="post">
            <div class="field">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="field">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="field">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="field">
                <select name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="actions">
                <button type="submit" name="register" class="btn">Register</button>
                <a href="login.php" class="btn secondary">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>
