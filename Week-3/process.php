<?php
session_start();
require_once __DIR__ . '/connect.php';

$devMode = true; // set to true on dev machine only

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sign-up.php');
    exit();
}

// Trim input
$name_raw     = $_POST['name']   ?? '';
$email_raw    = $_POST['email']  ?? '';
$password_raw = $_POST['password'] ?? '';

$name  = trim($name_raw);
$email = trim($email_raw);

// Basic validation
if ($name === '' || $email === '' || $password_raw === '') {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'All fields must be filled.'];
    header('Location: sign-up.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Please enter a valid email address.'];
    header('Location: sign-up.php');
    exit();
}

if (strlen($password_raw) < 8) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Password must be at least 8 characters.'];
    header('Location: sign-up.php');
    exit();
}

// Hash password properly
if (defined('PASSWORD_ARGON2ID')) {
    $password_hashed = password_hash($password_raw, PASSWORD_ARGON2ID);
} else {
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
}

if ($password_hashed === false) {
    error_log('password_hash failed for email: ' . $email);
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Something went wrong. Please try again later.'];
    header('Location: sign-up.php');
    exit();
}

// Prepare statement
$query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $connection->prepare($query);
if ($stmt === false) {
    // prepare failed
    error_log('Prepare failed: ' . $connection->error);
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Something went wrong. Please try again later.'];
    header('Location: sign-up.php');
    exit();
}

// Bind and execute
$stmt->bind_param('sss', $name, $email, $password_hashed);
$executed = $stmt->execute();

if ($executed) {
    // success
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Account created. Please log in.'];
    $stmt->close();
    $connection->close();
    header('Location: log-in.php');
    exit();
} else {
    // execution failed
    // Duplicate email check uses errno
    if ($connection->errno === 1062) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'The email ' . htmlspecialchars($email) . ' is already registered.'];
    } else {
        // Log the full error for devs
        error_log('DB error (errno ' . $connection->errno . '): ' . $stmt->error);
        if ($devMode) {
            // Careful: only show full message in dev
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'DB error: ' . htmlspecialchars($stmt->error)];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Something went wrong. Please try again later.'];
        }
    }

    $stmt->close();
    $connection->close();
    header('Location: sign-up.php');
    exit();
}
