<?php
include("auth.php");
checkLogin();
if (!isUser()) {
    header("Location: admin.php");
    exit();
}
?>
<h2>Welcome User</h2>
<p>Regular user dashboard.</p>
