<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
</head>

<?php
$message = '';
$username_email = '';
$sucsses = '';

if(isset($_GET['sp'])){
    $sucsses = 'SignUp Sucssesfully';
}

if (isset($_POST['login'])) {
    include "../config.php"; 


    $username_email = mysqli_real_escape_string($conn, $_POST['username-email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user_table WHERE username = '{$username_email}' OR email = '{$username_email}'";
    $result = mysqli_query($conn, $sql) or die("Query Failed");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (md5($password) === $row['password']) { 

            session_start(); 
            $_SESSION["id"] = $row['id'];
            $_SESSION["first_name"] = $row['first_name'];
            $_SESSION["last_name"] = $row['last_name'];
            $_SESSION["username"] = $row['username'];
            $_SESSION["email"] = $row['email'];
            $_SESSION["user_type"] = $row['user_type'];

            if($row['profile_image'] == ''){
                $_SESSION["profile_image"] = 'default-profile';
            }else{
                $_SESSION["profile_image"] = $row['profile_image'];
            }

            header("Location: ");
            exit();
        } else {
            $message = 'Username or password is incorrect.';
        }
    } else {
        $message = 'Username or password is incorrect.';
    }
}
?>

<body>
    <div class="container">
        <h2 style="color : #7932a5; text-algin : center"><b><?php echo $sucsses ?></b></h2>
        <h2>Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="username-email" placeholder="Email or Username" required 
                value="<?php echo htmlspecialchars($username_email); ?>"> 
            <input type="password" name="password" placeholder="Password" required>
            <p style="color:red; font-size:12px"><?php echo $message; ?></p>
    <br>
            <button type="submit" name="login">Login</button>
            <a href="forget-password.php" class="link">Forgot Password?</a>
            <a href="signup.php" class="link">Don't have an account? Sign up</a>
        </form>
    </div>

</body>

</html>
