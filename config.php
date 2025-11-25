<?php
$host = "sql210.infinityfree.com";   // MySQL Host Name (from your panel)
$user = "if0_40308957";              // MySQL Username
$pass = "Gb6taQt8aDa";  // Same password you use to log into InfinityFree/VistaPanel
$dbname = "if0_40308957_rbacdb";     // Your Database Name

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // Optional for testing
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
