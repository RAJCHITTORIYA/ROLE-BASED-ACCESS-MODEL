<?php
include("auth.php");
checkLogin();
if (!isAdmin()) {
    header("Location: user_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<div class="container">
    <div class="actions" style="justify-content: space-between;">
        <h2 style="margin:0;">Admin Dashboard</h2>
        <a class="btn secondary" href="logout.php">Logout</a>
    </div>
    <p class="muted">Only admins can see this page.</p>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>


<?php
include('config.php');

// Day 13 assignment starts from here  task :- Complete Role-Based CRUD

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();


  foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
        echo "<td><a href='user-edit.php?id=" . $user['id'] . "'>Edit</a> | <a href='user-delete.php?id=" . $user['id'] . "'>Delete</a></td>";
        echo "</tr>";
    }

?>
</table>
    </div>
</div>
</body>
</html>