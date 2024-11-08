<?php
include '../config.php';
session_start();

$user_id = $_GET['uid'];

$req_sql = "SELECT follower_id FROM followers WHERE user_id = $user_id AND id_status = 'accepted'";
$req_result = mysqli_query($conn, $req_sql) or die("Query Failed: " . mysqli_error($conn));

$request_ids = [];
while ($reqid_row = mysqli_fetch_assoc($req_result)) {
    $request_ids[] = $reqid_row['follower_id'];
}

if (!empty($request_ids)) {
    $request_ids_str = implode(',', $request_ids);
    $reqinfo_sql = "SELECT user_table.* FROM user_table WHERE user_table.id IN ($request_ids_str)";
    $reqinfo_result = mysqli_query($conn, $reqinfo_sql) or die("Query Failed: " . mysqli_error($conn));
} else {
    $reqinfo_result = false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Followers List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/followers.css" class="css">
</head>

<body>

    <div class="container" style="margin-top : 20px">
        <h2 style="text-align:center; margin-bottom : 20px">Followers</h2>
        <?php if ($reqinfo_result && mysqli_num_rows($reqinfo_result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($reqinfo_result)) { ?>
                <div class="follower">
                    <a class="follower-details" href="./user-profile.php?uid=<?php echo $row['id']  ?>" style="text-decoration:none">
                        <img src="../process/upload/<?php echo $row['profile_image'] ?>" alt="Profile Image">
                        <div>
                            <div class="username ">@<?php echo $row['username']; ?></div>
                            <div class="fullname"><?php echo $row['first_name'] . " " . $row['last_name']; ?></div>
                        </div>
                    </a>
                    <?php if($user_id == $_SESSION['id']) {?>
                        <a class="remove-btn follow-profile-btn" data-user-id='<?php echo $row['id']  ?>'>remove</a>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No followers yet.</p>
        <?php } ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('.remove-btn').click(function() {
            var user_id = $(this).data('user-id');

            $.ajax({
                url: '../process/remove.php',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                success: function(response) {
                    location.reload(true)
                }
            });
        });
    </script>

</body>

</html>