<?php

include '../config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
}

$user_id = $_GET['uid'];
if (isset($_GET['ld']) && $_GET['ld'] == 'true') {
    include 'loader.php';
}

$show = false;
$user_id2 = $_SESSION['id'];

$account_type = 'private';

$sql0 = "SELECT user_type FROM user_table WHERE user_type = 'public' AND id = $user_id";
$result0 = mysqli_query($conn, $sql0) or die("Query Failed: " . mysqli_error($conn));

if (mysqli_num_rows($result0) > 0 || $user_id == $_SESSION['id']) {
    $account_type = 'public';
    $show = true;
} else {
    $sql = "SELECT user_id FROM followers WHERE follower_id = $user_id2 AND user_id = $user_id AND id_status = 'accepted'";
    $result1 = mysqli_query($conn, $sql) or die("Query Failed: " . mysqli_error($conn));
    if (mysqli_num_rows($result1) > 0 || $user_id == $_SESSION['id']) {
        $show = true;
    }else{
        $show = false;
    }
}

$sql2 = "SELECT * FROM user_table WHERE id = $user_id;
    SELECT COUNT(*) as follower_count FROM followers WHERE user_id = $user_id AND id_status = 'accepted';
    SELECT COUNT(*) as following_count FROM followers WHERE follower_id = $user_id AND id_status = 'accepted';
    SELECT post.*, COUNT(likes.post_id) AS likes_count FROM post LEFT JOIN likes ON post.post_id = likes.post_id 
    WHERE post.user_id = $user_id GROUP BY post.post_id";

if (mysqli_multi_query($conn, $sql2)) {
    $result = mysqli_store_result($conn);
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    mysqli_next_result($conn);
    $result = mysqli_store_result($conn);
    $followers = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    mysqli_next_result($conn);
    $result = mysqli_store_result($conn);
    $following = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    mysqli_next_result($conn);
    $posts = [];
    if ($result = mysqli_store_result($conn)) {
        while ($post = mysqli_fetch_assoc($result)) {
            $posts[] = $post;
        }
        mysqli_free_result($result);
    }
} else {
    die("Query Failed: " . mysqli_error($conn));
}

$req_sql = "SELECT COUNT(*) as request_count FROM followers WHERE user_id = $user_id2 AND id_status = 'requested'";
$req_result = mysqli_query($conn, $req_sql) or die("Query Failed: " . mysqli_error($conn));

$request_ids = [];
while ($reqid_row = mysqli_fetch_assoc($req_result)) {
    $request_count = $reqid_row['request_count'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['username'] ?> Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/user-profile.css">
    <link rel="stylesheet" href="../css/bottom-bar.css">
</head>

<body>
    <div class="black-shade"></div>
    <div class="profile-container">
        <div class="profile-header">
            <?php if ($user_id == $user_id2 && $_SESSION['user_type'] == 'private') { ?>
                <div class="option" data-user-id="<?php echo $user_id ?>">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="28" viewBox="0 0 448 512">
                            <path fill="#FFFFFF" d="M224 0c-17.7 0-32 14.3-32 32l0 19.2C119 66 64 130.6 64 208l0 18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416l384 0c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8l0-18.8c0-77.4-55-142-128-156.8L256 32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3l-64 0-64 0c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z" />
                        </svg>
                        <p><b><?php echo $request_count; ?></b></p>

                        <div id="requests-container"></div>

                    </div>
                </div>

            <?php } ?>
            <div class="profile-pic">
                <img src="../process/upload/<?php echo $row['profile_image'] ?>" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <p>@<?php echo $row['username'] ?></p>
                <h2 class="username"><?php echo $row['first_name'] . " " . $row['last_name'] ?></h2>
                <div class="profile-stats">
                    <div class="stati">
                        <span class="number"><?php echo count($posts); ?></span> posts
            </div>
            <?php if($show){ ?>
                    <a class="stat" href="./followers.php?uid=<?php echo $user_id?>">
                        <span class="number followers"><?php echo $followers['follower_count']; ?></span> followers
                    </a>
                    <a class="stat" href="./following.php?uid=<?php echo $user_id?>">
                        <span class="number following"><?php echo $following['following_count']; ?></span> following
                    </a>
                    <?php
                     } else{?>
                        <a class="stat">
                        <span class="number followers"><?php echo $followers['follower_count']; ?></span> followers
                    </a>
                    <a class="stat">
                        <span class="number following"><?php echo $following['following_count']; ?></span> following
                    </a>
                    <?php } ;?>
                </div>
                <?php if ($_SESSION['id'] == $row['id']) {
                    echo '<a class="edit-profile-btn" href="update-profile.php?proid=' . $row['id'] . '">Edit Profile</a>';
                } elseif ($account_type == 'private') { ?>
                    <a class="follow-profile-btn unfollow-btn" style="display: none;" href="user-profile.php?uid=<?php echo $row['id']; ?>" data-user-id="<?php echo $row['id']; ?>">Unfollow</a>
                    <a class="follow-profile-btn request-btn"  data-user-id="<?php echo $row['id']; ?>">Request</a>
                <?php } else { ?>
                    <a class="follow-profile-btn follow-btn" href="user-profile.php?uid=<?php echo $row['id']; ?>" data-user-id="<?php echo $row['id']; ?>">Follow</a>
                <?php } ?>
            </div>
        </div>

        <div class="profile-posts">
            <div class="post-grid">
                <?php if ($show && !empty($posts)) {
                    foreach ($posts as $post) { ?>
                        <div class="post">
                            <a href="../pages/single-post.php?pid=<?php echo $post['post_id'] ?>" style="text-decoration: none; color : white">
                                <div class="image">
                                    <?php if (strpos($post['images'], '.mp4') !== false) { ?>
                                        <svg class="video-svg" xmlns="http://www.w3.org/2000/svg" height="40" width="40" viewBox="0 0 512 512">
                                            <path fill="#cdcbcb" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c7.6-4.2 16.8-4.1 24.3 .5l144 88c7.1 4.4 11.5 12.1 11.5 20.5s-4.4 16.1-11.5 20.5l-144 88c-7.4 4.5-16.7 4.7-24.3 .5s-12.3-12.2-12.3-20.9l0-176c0-8.7 4.7-16.7 12.3-20.9z" />
                                        </svg>
                                        <video width="100%" height="auto" class="post-image"
                                            src="../process/upload/<?php echo $post['images']; ?>" type="video/mp4">
                                        </video>
                                    <?php } else { ?>
                                        <img src="../process/upload/<?php echo $post['images']; ?>" alt="Post Image" class="post-image">
                                    <?php } ?>
                                    <div class="like-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="#ffffff" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z" />
                                        </svg>
                                        <p><?php echo $post['likes_count'] ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>


                <?php }
                } else{
                    echo "Account Is Private";
                }?>
            </div>
        </div>
    </div>

    <?php include './bottom-bar.php'; ?>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        $('.follow-btn').each(function() {
            var user_id = $(this).data('user-id');
            var button = $(this);

            $.ajax({
                url: '../process/check-follow.php',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === true) {
                        button.text('Unfollow');
                        button.removeClass('follow-profile-btn').addClass('unfollow-profile-btn');
                    } else {
                        button.text('Follow');
                        button.removeClass('unfollow-profile-btn').addClass('follow-profile-btn');
                    }
                }
            });

            $('.follow-btn').click(function() {
                var user_id = $(this).data('user-id');
                var button = $(this);

                $.ajax({
                    url: '../process/follow.php',
                    type: 'POST',
                    data: {
                        user_id: user_id
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === true) {
                            button.text('Unfollow');
                            button.removeClass('follow-profile-btn').addClass('unfollow-profile-btn');

                        } else {
                            button.text('Follow');
                            button.removeClass('unfollow-profile-btn').addClass('follow-profile-btn');

                        }
                    }
                });
            });

        });

        $('.request-btn').each(function() {
            var user_id = $(this).data('user-id');
            var button = $(this);

            $.ajax({
                url: '../process/check-request.php',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                success: function(response) {
                    if (response == 'request') {
                        button.text('Request');
                        button.removeClass('unfollow-profile-btn').addClass('follow-profile-btn ');
                    } else if (response === 'requested') {
                        button.text('Requested');
                        button.removeClass('follow-profile-btn').addClass('unfollow-profile-btn follow-btn ');
                    }else{
                        $('.unfollow-btn').show().removeClass('follow-profile-btn').addClass('unfollow-profile-btn');;
                        $('.request-btn').hide();
                    }
                }
            });

            $('.request-btn').click(function() {
                var user_id = $(this).data('user-id');
                var button = $(this);

                $.ajax({
                    url: '../process/request.php',
                    type: 'POST',
                    data: {
                        user_id: user_id
                    },
                    success: function(response) {
                        console.log(response);
                        if (response === 'requested') {
                            button.text('Requested');
                            button.removeClass('follow-profile-btn').addClass('unfollow-profile-btn');

                        } else {
                            button.text('Request');
                            button.removeClass('unfollow-profile-btn').addClass('follow-profile-btn');

                        }
                    }
                });
            });
            $('.unfollow-btn').click(function() {
                var user_id = $(this).data('user-id');

                $.ajax({
                    url: '../process/unfollow.php',
                    type: 'POST',
                    data: {
                        user_id: user_id
                    },
                    success: function(response) {
                        $('.unfollow-btn').hide().removeClass('follow-profile-btn').addClass('unfollow-profile-btn');;
                        $('.request-btn').show().removeClass('unfollow-profile-btn').addClass('follow-profile-btn');
                    }
                });
            });

        });

        $('.black-shade').click(function() {
            $('.option-content').hide();
            $('.black-shade').hide();
        });

        $('.option').click(function() {
            var user_id = $(this).data('user-id');
            $.ajax({
                url: '../process/check-request-list.php',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                success: function(response) {
                    $('#requests-container').html(response);
                    $('.option-content').show();
                    $('.black-shade').show();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.req-res-btn', function() {
            var follower_id = $(this).data('follower-id');
            var btn_type = $(this).data('btn-type');
            var user_id = $(this).data('user-id');

            $.ajax({
                url: '../process/request-response.php',
                type: 'POST',
                data: {
                    follower_id: follower_id,
                    btn_type: btn_type
                },
                success: function(response) {
                    console.log(response);
                    $.ajax({
                        url: '../process/check-request-list.php',
                        type: 'POST',
                        data: {
                            user_id: user_id
                        },
                        success: function(response) {
                            $('#requests-container').html(response);
                            location.reload(true)
                        }
                    });
                }
            });
        });



    });
</script>

</html>