<?php
    session_start();
    require_once "connect.php";

    // safe output helper
    function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

    // choose rehash target and options
    $useArgon = defined('PASSWORD_ARGON2ID');
    if ($useArgon) {
        $rehashAlgo = PASSWORD_ARGON2ID;
        $rehashOptions = [
            'memory_cost' => 131072, // 128 MB (in KB)
            'time_cost'   => 4,
            'threads'     => 2,
        ];
    } else {
        $rehashAlgo = PASSWORD_DEFAULT;
        $rehashOptions = ['cost' => 12];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // basic server-side validation
        if ($email === '' || $password === '') {
            echo '<script>alert("Please fill all of the fields.");</script>';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>alert("Invalid email address.");</script>';
        } else {
            // fetch user
            $stmt = $connection->prepare("SELECT id, name, password FROM users WHERE email = ? LIMIT 1");
            if ($stmt === false) {
                // log and show generic message
                error_log("DB prepare failed: " . $connection->error);
                echo '<script>alert("Something went wrong. Please try again later.");</script>';
            } else {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($user_id, $user_name, $stored_hash);
                    $stmt->fetch();

                    $verified = false;
                    $needs_rehash = false;

                    // 1) Prefer password_verify (for password_hash output)
                    if (password_verify($password, $stored_hash)) {
                        $verified = true;
                        // check if we should rehash to stronger algorithm/options
                        if (password_needs_rehash($stored_hash, $rehashAlgo, $rehashOptions)) {
                            $needs_rehash = true;
                        }
                    } else {
                        // 2) Fallback: legacy crypt($password, $stored_hash) check
                        // Only attempt fallback if stored_hash looks like crypt-SHA variants.
                        // crypt SHA-256 ($5$) and SHA-512 ($6$) output usually start with $5$ or $6$
                        if (is_string($stored_hash) && preg_match('#^\$(5|6)\$#', $stored_hash)) {
                            if (hash_equals($stored_hash, crypt($password, $stored_hash))) {
                                $verified = true;
                                // Since it's legacy fast-hash, mark for rehash to modern algorithm
                                $needs_rehash = true;
                            }
                        }
                    }

                    if ($verified) {
                        // If needed, compute new hash and update DB
                        if ($needs_rehash) {
                            $newHash = password_hash($password, $rehashAlgo, $rehashOptions);
                            if ($newHash !== false) {
                                $up = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
                                if ($up !== false) {
                                    $up->bind_param("si", $newHash, $user_id);
                                    if (!$up->execute()) {
                                        // log failure but do NOT block login
                                        error_log("Failed to update password hash for user {$user_id}: " . $up->error);
                                    }
                                    $up->close();
                                } else {
                                    error_log("DB prepare failed (update rehash): " . $connection->error);
                                }
                            } else {
                                error_log("password_hash() failed while rehashing for user {$user_id}");
                            }
                        }

                        // Successful login
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_name'] = $user_name;
                        session_regenerate_id(true);
                        $stmt->close();
                        $connection->close();
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        // auth failed
                        echo '<script>alert("Invalid email or password.");</script>';
                    }
                } else {
                    echo '<script>alert("Invalid email or password.");</script>';
                }
                $stmt->close();
            }
        }
    }
?>

<?php
    session_start();

    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $class = ($flash['type'] === 'success') ? 'flash-success' : 'flash-error';

        echo '<div class="' . htmlspecialchars($class) . '">'
        . htmlspecialchars($flash['msg'])
        . '</div>';

        unset($_SESSION['flash']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In page</title>
    <link rel="stylesheet" href="css/log-in.css">
    <link rel="stylesheet" href="css/flash-status.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="box">
        <div class="page-1">
            <div class="pageRow-1">
                <h1 class="title" style="font-family: 'intro script';">Logo</h1>
            </div>
            <div class="pageRow-2">
                <h2 style="text-align: center; font-family: Arial, Helvetica, sans-serif;" class="sizefontLogin">
                    Log In<br>
                </h2>
                <p style="text-align: center; font-family: Arial, Helvetica, sans-serif;" class="sizefontSub">
                    Lorem ipsum, dolor sit amet adipisicing elit.
                </p>
                <form action="" class="formlogin" id="formloginVal" method="POST">
                    <label style="font-family: Arial, Helvetica, sans-serif;" class="sizefont">
                        Email*<br>
                    </label>
                    <input type="email" id="emailLogin" class="adj-row" name="email"><br>
                    <label style="font-family: Arial, Helvetica, sans-serif;" class="sizefont">
                        Password* 
                        <u style="margin-left: 240px; ">
                            <a href="#" class="hiddenButton sizefont" style="font-family: Arial, Helvetica, sans-serif;">
                                Forgot your password?<br>
                            </a>
                        </u>
                    </label>
                    <input type="password" id="pwLogin" class="adj-row" name="password"><br>
                    <input type="submit" value="Log in" class="adj-loginButton">
                </form>
                <button class="adj-googleButton">
                    <i class='bx bxl-google' style="font-size: 14px;"></i> 
                    Log in with google
                </button>
                <p style="text-align: center; font-family: Arial, Helvetica, sans-serif;" class="sizefont">
                    Don't have an account?
                    <a href="#" class="hiddenButton sizefont" style="font-family: Arial, Helvetica, sans-serif;">
                        Sign up
                    </a>
                </p>
            </div>
            <div>
                <p class="footer">© 2025 Kelompok 6</p>
            </div>
        </div>
        <script src="js/log-in.js"></script>
        <div class="page-2">
            <img src="img/placeholder-4.png" alt="image" />
        </div>
    </div>
</body>