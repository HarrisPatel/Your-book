<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/forgetpassword.css">

</head>
<?php

$sent = '';

if (isset($_POST['login'])) {
    include "../config.php"; 

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT email FROM user_table WHERE email = '{$email}'";
    $result = mysqli_query($conn, $sql) or die("Query Failed");

    if (mysqli_num_rows($result) > 0) {
        $sent = true;

    } else {
        $sent = false;
    }
}
?>
<body>

    <div class="container">
        <h2>Forgot Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="email" placeholder="Enter your email" required>
            <p style="color:red; font-size:12px"><?php if ($sent == false) echo 'Wrong Email'; ?></p>
            <br>
            <button type="submit">Reset Password</button>
            <a href="login.php" class="link">Back to Login</a>
        </form>
    </div>

</body>
</html>
