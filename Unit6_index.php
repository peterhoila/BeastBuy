<!DOCTYPE html>
<html lang="en">
<?php
date_default_timezone_set("America/Denver");
include 'Unit6_database.php';
$conn = getConnection();
?>

<head>
    <title>Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="Unit6_login.css">
    <link rel="stylesheet" href="Unit6_common.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>
    <?php include 'Unit6_header.php' ?>
    <p>Welcome! Please login or select Continue as Guest to begin.</p>
    <?php
    if (isset($_GET['err'])) {
        echo '<p style="color: red; margin: 20px; font-weight:bold">' . htmlspecialchars($_GET['err']) . '</p>';
    }
    ?>
    <div class="container">
        <form action="Unit6_login.php" method="post">
            <label for="email" style="font-weight:bold">Email</label><br>
            <input type="email" id="email" name="email" placeholder="Enter Email" required><br>

            <label for="password" style="font-weight:bold">Password</label><br>
            <input type="password" id="password" name="password" placeholder="Enter Password" required><br>

            <button type="submit" class="submit-btn">Login</button>
            <label for="remember">
                <input type="checkbox" id="remember" name="remember"> Remember me
            </label>
            <p style="float:right">Forgot<a href="#">password?</a></p>
        </form>

    </div>
    <button class="guest-btn" onclick="window.location.href='Unit6_store.php'">Continue as Guest</button>
    <?php include 'Unit6_footer.php' ?>

</body>

</html>