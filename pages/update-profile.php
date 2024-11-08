<?php
include 'loader.php';
$message = '';
$message1 = '';

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../config.php';
    $profile_id = $_SESSION['id'];

    $stmt = $conn->prepare("SELECT * FROM user_table WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $file_name = $row['profile_image'];

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $error = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext_array = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext_array));
        $extensions = array("jpeg", "jpg", "png");

        if (empty($error)) {
            move_uploaded_file($file_tmp, "../process/upload/" . $file_name);
        } else {
            foreach ($error as $err) {
                echo $err . "<br>";
            }
            die();
        }
    }

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $privacy = $_POST['privacy'];

    $sql = "SELECT username FROM user_table WHERE username = '{$username}' AND id != $profile_id";
    $result = mysqli_query($conn, $sql) or die("Query failed");

    $sql1 = "SELECT email FROM user_table WHERE email = '{$email}' AND id != $profile_id";
    $result1 = mysqli_query($conn, $sql1) or die("Query failed");

    $row = mysqli_num_rows($result);
    $row1 = mysqli_num_rows($result1);
    $row1 = mysqli_num_rows($result1);

    if ($row > 0) {
        $message = "Username Already Exists";
    } elseif ($row1 > 0) {
        $message1 = "Email Already Exists";
    } else {
        $_SESSION['message'] = '';
        $sql1 = "UPDATE user_table SET first_name = '{$first_name}', last_name = '{$last_name}', username = '{$username}', email = '{$email}', profile_image = '{$file_name}', user_type = '{$privacy}' WHERE id = $profile_id";
        $result1 = mysqli_query($conn, $sql1) or die("Query failed");

        if ($privacy == 'public') {
            echo $privacy;

            $sql2 = "SELECT follower_id FROM followers WHERE user_id = $profile_id AND id_status = 'requested'";
            $result2 = mysqli_query($conn, $sql2) or die("Query Failed: " . mysqli_error($conn));

            if (mysqli_num_rows($result2) > 0) {
                $follower_user_ids = [];

                while ($rows2 = mysqli_fetch_assoc($result2)) {
                    $follower_user_ids[] = $rows2['follower_id'];
                }

                $user_ids_string = implode(',', $follower_user_ids);

                if (!empty($user_ids_string)) {
                    $sql3 = "UPDATE followers SET id_status = 'accepted' WHERE follower_id IN ($user_ids_string)";
                    $result3 = mysqli_query($conn, $sql3) or die("Query Failed: " . mysqli_error($conn));
                }
            }
        }


        if ($result1) {
            $message = '';
            $message1 = '';
            $_SESSION["first_name"] = $first_name;
            $_SESSION["last_name"] = $last_name;
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["profile_image"] = $file_name;
            $_SESSION["user_type"] = $privacy;
            header("Location: user-profile.php?uid=$profile_id&&ld=true");
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
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <div class="image-container">
                    <div class="edit-image">
                        <div class="black-shade"></div>
                        <div class="camera">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 512 512">
                                <path fill="#ffffff" d="M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                            </svg>
                        </div>
                        <img class="profile-image" src="../process/upload/<?php echo $_SESSION['profile_image']; ?>" alt="Profile">
                    </div>
                    <br>
                </div>
                <div class="mb-3" style="display: none;">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="select-image" name="image" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" placeholder="First Name" required value="<?php echo $_SESSION['first_name'] ?>">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" required value="<?php echo $_SESSION['last_name'] ?>">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required value="<?php echo $_SESSION['username'] ?>">
                    <p style="color : red;font-size:12px"><?php echo $message ?></p>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo $_SESSION['email'] ?>">
                    <p style="color : red;font-size:12px"><?php echo $message1 ?></p>
                </div>
                <label class="radio-label">
                    <input type="radio" name="privacy" value="public" <?php echo ($_SESSION['user_type'] == 'public') ? 'checked' : ''; ?>> Public
                </label>
                <label class="radio-label">
                    <input type="radio" name="privacy" value="private" <?php echo ($_SESSION['user_type'] == 'private') ? 'checked' : ''; ?>> Private
                </label>
                <button type="submit" class="w-100"><b>Update Profile</b></button>
            </form>
        </div>
    </div>
    <?php include 'bottom-bar.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.edit-image').on('click', function() {
                $('#select-image').click();
            });
            $('#select-image').change(function() {
                console.log("File input changed");
                const file = this.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $('.profile-image').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    var old_path = "../process/upload/<?php echo $_SESSION['profile_image']; ?>";
                    $('.profile-image').attr('src', old_path).show();
                }
            });
        });
    </script>
</body>

</html>