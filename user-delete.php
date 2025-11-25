<?php
include("config.php");
include("auth.php");
checkLogin();

if (!isAdmin()) {
    header("Location: user_dashboard.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin.php");
exit();
?>
