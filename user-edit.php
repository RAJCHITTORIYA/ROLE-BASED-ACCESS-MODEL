<?php
include("config.php");
include("auth.php");
checkLogin();

if (!isAdmin()) {
    header("Location: user_dashboard.php");
    exit();
}

// Get the user ID from URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "User not found!";
        exit();
    }
}

// Update when form is submitted
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $update->execute([$username, $email, $role, $id]);

    header("Location: admin.php");
    exit();
}
?>

<h2>Edit User</h2>
<form method="post">
    <label>Name:</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

    <label>Role:</label>
    <select name="role" required>
        <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
        <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
    </select><br><br>

    <button type="submit" name="update">Update</button>
</form>

<p><a href="admin.php">Back to Admin Panel</a></p>
