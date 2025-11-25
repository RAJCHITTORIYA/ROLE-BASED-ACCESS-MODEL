<?php
/*
  Google Login (Beginner-friendly, no Composer)
  --------------------------------------------
  What this file does:
  1) Sends the user to Google to pick an account
  2) Receives a code back from Google
  3) Exchanges the code for an access token
  4) Uses the token to get the user's email/name
  5) Logs the user in (create if new) and redirects
*/

session_start();
include('config.php');

// 1) Your Google OAuth app credentials
//$clientId = "688207479857-un3u6qlas3q3t726t2tiaaujunj6hrc0.apps.googleusercontent.com";
//$clientSecret = "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";

// 2) Build the redirect URL for this file (must be added in Google Console)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$scheme = $isHttps ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$path = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/google_login.php';
$redirectUri = $scheme . '://' . $host . $path;

// 3) Google endpoints
$authUrl =  "https://accounts.google.com/o/oauth2/v2/auth";
$tokenUrl = "https://oauth2.googleapis.com/token";
$userInfoUrl = "https://www.googleapis.com/oauth2/v2/userinfo";

// Small helpers: use cURL if available, otherwise file_get_contents
function postForm($url, $data) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => http_build_query($data),
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$response, $status];
    }
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded",
            'content' => http_build_query($data),
            'ignore_errors' => true
        ]
    ]);
    $response = @file_get_contents($url, false, $context);
    $status = 0;
    global $http_response_header;
    if (isset($http_response_header[0]) && preg_match('#\s(\d{3})\s#', $http_response_header[0], $m)) {
        $status = (int)$m[1];
    }
    return [$response, $status];
}

function getJsonWithBearer($url, $token) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token],
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$response, $status];
    }
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Authorization: Bearer ' . $token,
            'ignore_errors' => true
        ]
    ]);
    $response = @file_get_contents($url, false, $context);
    $status = 0;
    global $http_response_header;
    if (isset($http_response_header[0]) && preg_match('#\s(\d{3})\s#', $http_response_header[0], $m)) {
        $status = (int)$m[1];
    }
    return [$response, $status];
}

// A) No code yet: send user to Google's account chooser
if (!isset($_GET['code'])) {
    $loginUrl = $authUrl . "?" . http_build_query([
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'email profile',
        'access_type' => 'online',
        'prompt' => 'select_account'
    ]);
    header("Location: " . $loginUrl);
    exit;
}

// B) We got a code back from Google: exchange it for a token
$code = $_GET['code'];
$tokenData = [
    'code' => $code,
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code'
];
list($tokenResponse, $tokenStatus) = postForm($tokenUrl, $tokenData);
if ($tokenResponse === false || $tokenStatus >= 400) {
    die('Could not get token from Google. Please check your redirect URI in Google Console.');
}
$tokenInfo = json_decode($tokenResponse, true);
if (!isset($tokenInfo['access_token'])) {
    die('Google did not return an access token.');
}
$accessToken = $tokenInfo['access_token'];

// C) Use the token to get the user's info (email, name)
list($userResponse, $userStatus) = getJsonWithBearer($userInfoUrl, $accessToken);
if ($userResponse === false || $userStatus >= 400) {
    die('Could not fetch user info from Google.');
}
$user = json_decode($userResponse, true);
$email = isset($user['email']) ? $user['email'] : null;
$name = isset($user['name']) ? $user['name'] : 'Google User';
if (!$email) {
    die("Your Google account did not return an email. Please grant 'email' permission.");
}

// D) Create or find the user in our database, then log them in
try {
    $emailNormalized = strtolower(trim($email));

    // find existing user (case-insensitive)
    $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(email) = :email LIMIT 1");
    $stmt->bindParam(":email", $emailNormalized);
    $stmt->execute();
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $_SESSION['user'] = $existingUser['username'];
        $_SESSION['email'] = $existingUser['email'];
        $_SESSION['role'] = $existingUser['role'];
    } else {
        $insert = $conn->prepare("INSERT INTO users (username, email, role) VALUES (:username, :email, 'user')");
        $insert->bindParam(":username", $name);
        $insert->bindParam(":email", $emailNormalized);
        $insert->execute();

        $_SESSION['user'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'user';
    }

    // Go to the correct dashboard
    if (strtolower($_SESSION['role']) === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: user_dashboard.php');
    }
    exit;
} catch (PDOException $e) {
    die('Database error: ' . $e->getMessage());
}
?>
