<?php
$message = '';
$message1 = '';
$message2 = '';
$username = '';
$email = "";
$first_name = '';
$last_name = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config.php';

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $temp_password = mysqli_real_escape_string($conn, $_POST['password']);
    $registration_time = date('Y-m-d');
    $profile_image = 'default-profile.jpg';

    $uppercase = preg_match('@[A-Z]@', $temp_password);
    $lowercase = preg_match('@[a-z]@', $temp_password);
    $number    = preg_match('@[0-9]@', $temp_password);
    $minLength = strlen($temp_password) >= 8;
    $maxLength = strlen($temp_password) <= 16;

    if (!$uppercase || !$lowercase || !$number || !$minLength || !$maxLength) {
        $message2 = "<ul>
                        <li style='color:" . ($minLength ? "green" : "red") . "'>Minimum 8 Characters long</li>
                        <li style='color:" . ($maxLength ? "green" : "red") . "'>Maximum 16 Characters long</li>
                        <li style='color:" . ($uppercase ? "green" : "red") . "'>At least 1 uppercase character</li>
                        <li style='color:" . ($lowercase ? "green" : "red") . "'>At least 1 lowercase character</li>
                        <li style='color:" . ($number ? "green" : "red") . "'>At least 1 number</li>
                    </ul>";
    }

    $sql = "SELECT username FROM user_table WHERE username = '{$username}'";
    $result = mysqli_query($conn, $sql) or die("Query failed");

    $sql1 = "SELECT email FROM user_table WHERE email = '{$email}'";
    $result1 = mysqli_query($conn, $sql1) or die("Query failed");

    if (mysqli_num_rows($result) > 0) {
        $message = "Username already exists";
    }

    if (mysqli_num_rows($result1) > 0) {
        $message1 = "Email already exists";
    }

    if (empty($message) && empty($message1) && empty($message2)) {
        $password = md5($_POST['password']); 

        $sql2 = "INSERT INTO user_table (first_name, last_name, username, email, password, profile_image, registration_time)
                 VALUES ('{$first_name}', '{$last_name}', '{$username}', '{$email}', '{$password}', '{$profile_image}', '{$registration_time}')";
        $result2 = mysqli_query($conn, $sql2) or die("Query failed");

        if ($result2 > 0) {
            header("Location: login.php?sp=true");
        } else {
            echo "Something went wrong.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/signup.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Sign Up</h2>
        <form id="signupForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div style="display:flex; justify-content: space-between; gap:12px">
                <input type="text" id="first_name" name="first_name" placeholder="First Name" required
                    value="<?php echo htmlspecialchars($first_name); ?>">
                <input type="text" id="last_name" name="last_name" placeholder="Last Name" required
                    value="<?php echo htmlspecialchars($last_name); ?>">
            </div>
            <input type="text" id="username" name="username" placeholder="Username" required
                value="<?php echo htmlspecialchars($username); ?>">
            <p style="color : red;font-size:12px"><?php echo $message ?></p>
            <input type="email" id="email" name="email" placeholder="Email" required
                value="<?php echo htmlspecialchars($email); ?>">
            <p style="color : red;font-size:12px"><?php echo $message1 ?></p>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <div style="color : red;font-size:12px"><?php echo $message2 ?></div>
            <br><br>

            <button type="submit">Sign Up</button>
            <a href="login.php" class="link">Already have an account? Login</a>
        </form>
    </div>

</body>

</html>