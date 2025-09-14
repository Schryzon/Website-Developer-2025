<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In page</title>
    <link rel="stylesheet" href="css/log-in.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="box">
        <div class="page-1">
            <div class="pageRow-1">
                <h1 class="title" style="font-family: 'Intro Script';">Logo</h1>
            </div>
            <div class="pageRow-2">
                <h2 style="text-align: center; font-family: Arial, Helvetica, sans-serif;" class="sizefontLogin">
                    Log In<br>
                </h2>
                <p style="text-align: center; font-family: Arial, Helvetica, sans-serif;" class="sizefontSub">
                    Lorem ipsum, dolor sit amet adipisicing elit.
                </p>
                <form action="" class="formlogin">
                    <label style="font-family: Arial, Helvetica, sans-serif;" class="sizefont">
                        Email*<br>
                    </label>
                    <input type="email" class="adj-row"><br>
                    <label style="font-family: Arial, Helvetica, sans-serif;" class="sizefont">
                        Password* 
                        <u style="margin-left: 215px; ">
                            <a href="#" class="hiddenButton sizefont" style="font-family: Arial, Helvetica, sans-serif;">
                                Forgot your password?<br>
                            </a>
                        </u>
                    </label>
                    <input type="password" class="adj-row"><br>
                    <input type="submit" value="Log in" class="adj-loginButton">
                </form>
                <button class="adj-googleButton">
                    <i class='bx bxl-google' style="font-size: 14px;"></i> 
                    Log in with Google
                </button>
                <p style="text-align: center; font-family: Arial, Helvetica, sans-serif;" class="sizefont">
                    Don't have an account?
                    <a href="./sign-up.php" class="hiddenButton sizefont" style="font-family: Arial, Helvetica, sans-serif;">
                        Sign up
                    </a>
                </p>
            </div>
            <div>
                <p class="footer">© 2025 Kelompok 6</p>
            </div>
        </div>
        <head class="page-2">
            <img src="img/placeholder-4.png" alt="image" />
        </head>
    </div>
</body>