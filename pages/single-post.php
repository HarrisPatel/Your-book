<?php
session_start();
include '../config.php';

$post_id = $_GET['pid'];

$sql = "SELECT * FROM post 
        INNER JOIN user_table ON post.user_id = user_table.id 
        WHERE post.post_id = $post_id";

$result = mysqli_query($conn, $sql) or die("Query Failed: " . mysqli_error($conn));
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details</title>

    <link rel="stylesheet" href="../css/feed.css" class="css">
    <link rel="stylesheet" href="../css/dropdown.css" class="css">
    <link rel="stylesheet" href="../css/feed-modal.css" class="css">
    <link rel="stylesheet" href="../css/single-post.css" class="css">
    <link rel="stylesheet" href="../css/bottom-bar.css" class="css">
</head>

<body>
    <div class="container">


        <div class="post">
            <div class="post-header">
                <a class="post-profile" href="user-profile.php?uid=<?php echo $row['user_id'] ?>">
                    <img src="../process/upload/<?php echo $row['profile_image'] ?>" alt="User Profile" class="profile-pic" style='border-radius : 100px; width : 40px;height : 40px'>
                    <h4><?php echo $row['username'] ?></h4>
                </a>
                <div class="post-options">
                    <div class="dropdown">
                        <p class="options-btn" style="padding: 0 20px;"><b>. . .</b></p>
                        <div class="dropdown-content">
                            <?php
                            if ($_SESSION['id'] == $row['user_id']) {
                                echo '<a href="update-post.php?pid=' . $row['post_id'] . '">Edit Post</a>';
                                echo '<a href="../process/delete-post.php?pid=' . $row['post_id'] . '">Delete Post</a>';
                            }
                            echo '<a>Report Post</a>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="post-content">
                <?php
                $file_extension = pathinfo($row['images'], PATHINFO_EXTENSION);

                if ($file_extension == 'mp4') {
                    echo "<video class='post-video' width='100%' controls>
                                    <source src='../process/upload/" . $row['images'] . "' type='video/mp4'>
                                    </video>";
                } else {
                    echo "<img src='../process/upload/" . $row['images'] . "' alt='post' class='post-image' width='100%'>";
                }
                ?>
                <p><b><?php echo $row['title'] ?></b></p>
                <p><?php echo $row['description'] ?></p>
            </div>

            <div class="post-actions">
                <button style="border:none;background-color:#2d2d2d ;cursor:pointer;"
                    class="action-btn like-btn"
                    data-post-id="<?php echo $row['post_id']; ?>">
                    <svg class="like-icon" xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 512 512">
                        <path fill="#ffffff" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z" />
                    </svg>
                    <svg class="dislike-icon" style="display: none" xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 512 512">
                        <path fill="#d60a0a" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z" />
                    </svg>
                </button>

                <button class="comment-btn" style="border:none;background-color:#2d2d2d;cursor:pointer;"
                    data-post-id="<?php echo $row['post_id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 512 512">
                        <path fill="#ffffff" d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z" />
                    </svg></button>
            </div>
            <div class="show-likes">
                <p style="margin-top: 0;"><strong id="likes-count-<?php echo $row['post_id']; ?>">
                        <?php
                        $likes_query = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = " . $row['post_id'];
                        $likes_result = mysqli_query($conn, $likes_query);
                        $likes_row = mysqli_fetch_assoc($likes_result);
                        echo $likes_row['like_count'];
                        ?>
                    </strong> Likes</p>
                <p class="time"><?php echo $row['upload_time'] ?></p>

            </div>
            <div id="commentModal" class="modal">
                <div class="modal-content">
                    <div style="overflow-y: auto;">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h2 style="text-align: center;color:black">Comments</h2>
                        <div class="comment-section" id="commentSection">
                            <!-- Comments will be loaded here dynamically -->
                        </div>
                        <div class="comment-input">
                            <div class="comment">
                                <img src="../process/upload/<?php echo $_SESSION['profile_image'] ?>" alt="User" style="box-shadow: 0px 8px 16px rgba(0.4, 0.4, 0.4, 0.4);">
                            </div>
                            <textarea id="commentInput" placeholder="Add a comment..."></textarea>
                            <button id="submitComment">Send</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
<?php include 'bottom-bar.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        $('.like-btn').each(function() {
            var post_id = $(this).data('post-id');
            var button = $(this);

            $.ajax({
                url: '../process/check-like.php',
                type: 'POST',
                data: {
                    post_id: post_id
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === true) {
                        button.find('.dislike-icon').show();
                        button.find('.like-icon').hide();
                    } else {
                        button.find('.like-icon').show();
                        button.find('.dislike-icon').hide();
                    }
                }
            });
        });

        $('.like-btn').click(function() {
            var post_id = $(this).data('post-id');
            var button = $(this);

            $.ajax({
                url: '../process/like-post.php',
                type: 'POST',
                data: {
                    post_id: post_id
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 'liked') {
                        button.find('.dislike-icon').show();
                        button.find('.like-icon').hide();


                    } else {
                        button.find('.like-icon').show();
                        button.find('.dislike-icon').hide();
                    }

                    $.ajax({
                        url: '../process/get-likes-count.php',
                        type: 'POST',
                        data: {
                            post_id: post_id
                        },
                        success: function(count) {
                            $('#likes-count-' + post_id).text(count);
                        }
                    });
                }
            });
        });


        $('.close-btn').click(function() {
            $('#commentModal').hide();
        });

        let activePostId = null;

        $('.comment-btn').click(function() {
            activePostId = $(this).data('post-id');
            $('#commentModal').show();

            $.ajax({
                url: '../process/get-comments.php',
                type: 'POST',
                data: {
                    post_id: activePostId
                },
                success: function(response) {
                    $('#commentSection').html(response);
                }
            });
        });

        $('#submitComment').click(function() {
            var commentText = $('#commentInput').val().trim();

            if (commentText === '') {
                alert('Please enter a comment!');
                return;
            }

            $.ajax({
                url: '../process/add-comments.php',
                type: 'POST',
                data: {
                    post_id: activePostId,
                    comment: commentText
                },
                success: function(response) {
                    if (response === 'success') {
                        $.ajax({
                            url: '../process/get-comments.php',
                            type: 'POST',
                            data: {
                                post_id: activePostId
                            },
                            success: function(newComments) {
                                $('#commentSection').html(newComments);
                                $('#commentInput').val('');
                            }
                        });
                    } else {
                        alert('Error submitting comment.');
                    }
                }
            });
        });



    });
</script>


</html>