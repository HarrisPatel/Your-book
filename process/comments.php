<?php
$message = '';
$message1 = '';

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config.php';
    $profile_id = $_SESSION['id'];

    $sql = "SELECT * FROM user_table WHERE id = $profile_id";
    $result = mysqli_query($conn, $sql) or die("Query Failed");
    $row = mysqli_fetch_assoc($result);

    $file_name = $row['profile_image'];

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $error = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext_array = explode('.', $file_name);
        $file_ext = end($file_ext_array);
        $extensions = array("jpeg", "jpg", "png");

        if ($file_size > 2097152) {
            $error[] = "File size must be under 2MB";
        }

        if (empty($error)) {
            move_uploaded_file($file_tmp, "../process/upload/" . $file_name);
        } else {
            print_r($error);
            die();
        }
    }

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT username FROM user_table WHERE username = '{$username}' AND id != $profile_id";
    $result = mysqli_query($conn, $sql) or die("Query failed");

    $sql1 = "SELECT email FROM user_table WHERE email = '{$email}' AND id != $profile_id";
    $result1 = mysqli_query($conn, $sql1) or die("Query failed");

    $row = mysqli_num_rows($result);
    $row1 = mysqli_num_rows($result1);

    if ($row > 0) {
        $message = "Username Already Exists";
    } elseif ($row1 > 0) {
        $message1 = "Email Already Exists";
    } else {
        $_SESSION['message'] = '';
        $sql1 = "UPDATE user_table SET first_name = '{$first_name}', last_name = '{$last_name}', username = '{$username}', email = '{$email}', profile_image = '{$file_name}' WHERE id = $profile_id";

        $result1 = mysqli_query($conn, $sql1) or die("Query failed");

        if ($result1) {
            echo '<pre>';
            print_r($_FILES);
            echo '</pre>';
            $message = '';
            $message1 = '';
            $_SESSION["first_name"] = $first_name;
            $_SESSION["last_name"] = $last_name;
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["profile_image"] = $file_name;
            // header("Location: user-profile.php?uid=$profile_id");
        } else {
            echo "Something went wrong";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/update-profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bottom-bar.css">

</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2><b>Update Profile</b></h2>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                <div class="image-container">
                    <img class="profile-image" src="../process/upload/<?php echo $_SESSION['profile_image']; ?>" alt="Profile">
                </div>
                <br>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="select-image" name="image" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" placeholder="First Name" required
                        value="<?php echo $_SESSION['first_name'] ?>">
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" required
                        value="<?php echo $_SESSION['last_name'] ?>">
                </div>


                <div class="mb-3">
                    <label for="description" class="form-label">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required
                        value="<?php echo $_SESSION['username'] ?>">
                    <p style="color : red;font-size:12px"><?php echo $message ?></p>
                </div>


                <div class="mb-3">
                    <label for="description" class="form-label">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" required
                        value="<?php echo $_SESSION['email'] ?>">
                    <p style="color : red;font-size:12px"><?php echo $message1 ?></p>
                </div>



                <button type="submit" class=" w-100"><b>Update Profile</b></button>
            </form>
        </div>
    </div>
    <?php include 'bottom-bar.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
   $(document).ready(function() {
    $('#select-image').change(function() {
        const file = this.files[0];
        console.log('file-succ')

        if (file) {
            const reader = new FileReader();
            console.log('if-file-succ')

            reader.onload = function(e) {
                $('.profile-image').attr('src', e.target.result).show(); 
        console.log('done')

            };
            reader.readAsDataURL(file); 
        } else {
            var old_path = "../process/upload/<?php echo $_SESSION['profile_image']; ?>";
            $('.profile-image').attr('src', old_path).show();
        console.log('failed')

        }
    });
});

</script>

</html> make it working form without any  bug 