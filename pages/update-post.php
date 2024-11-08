<?php
include 'loader.php';

include '../config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
}


$post_id = $_GET['pid'];

$sql = "SELECT * FROM post INNER JOIN user_table ON post.user_id = user_table.id WHERE post_id = $post_id";
$result = mysqli_query($conn, $sql) or die("Query Faild");
$row = mysqli_fetch_assoc($result)

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/update-post.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bottom-bar.css">

</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2><b>Update Post</b></h2>
            <form action="../process/update-post.php?pid=<?php echo $post_id ?>" method="POST" enctype="multipart/form-data">
                <div class="image-container">
                    <?php if (strpos($row['images'], '.mp4') !== false || strpos($row['images'], '.webm') !== false): ?>
                        <video class="pre-video" src="../process/upload/<?php echo $row['images']; ?>" controls style="width:100%; height:300px;">
                            Your browser does not support the video tag.
                        </video>
                        <img class="post-image pre-image" src="" alt="post" style="display:none;"> <!-- Hide the image initially -->
                    <?php else: ?>
                        <img class="post-image pre-image" src="../process/upload/<?php echo $row['images']; ?>" alt="post" style="width:100%; height:auto;">
                        <video class="pre-video" src="" controls style="display:none; width:100%; height:300px;"></video> <!-- Hide the video initially -->
                    <?php endif; ?>
                </div>
                <br><br>
                <div class="mb-3">
                    <label for="image" class="form-label">Change Image</label>
                    <button class="new-image" type="button">Select New Image/Video</button>
                    <input type="file" id="select-image" name="image" accept="image/*,video/*" style="display: none;">
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" value="<?php echo $row['title'] ?>" id="title" name="title" placeholder="Add Title" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" value="<?php echo $row['description'] ?>" id="description" name="description" placeholder="Add Description" required>
                </div>

                <button type="submit" class="w-100"><b>Update Post</b></button>
            </form>

        </div>
    </div>
    <?php include 'bottom-bar.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.new-image').on('click', function() {
            $('#select-image').click();
        });

        $('#select-image').change(function() {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const fileType = file.type;

                    if (fileType.startsWith('video/')) {
                        $('.post-image').hide();
                        $('.pre-video').attr('src', e.target.result).show();
                    } else if (fileType.startsWith('image/')) {
                        $('.pre-video').hide();
                        $('.post-image').attr('src', e.target.result).show();
                    }
                };
                reader.readAsDataURL(file);
            } else {

                const old_path = "../process/upload/<?php echo $row['images']; ?>";
                if (old_path.endsWith('.mp4') || old_path.endsWith('.webm')) {
                    $('.post-image').hide();
                    $('.pre-video').attr('src', old_path).show();
                } else {
                    $('.pre-video').hide();
                    $('.post-image').attr('src', old_path).show();
                }
            }
        });
    });
</script>

</html>