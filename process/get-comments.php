<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    
    $sql = "SELECT c.comment, u.username, u.profile_image FROM comments c
          JOIN user_table u ON c.user_id = u.id
          WHERE c.post_id = $post_id ORDER BY c.created_at ASC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="comment">';
            echo '<img src="../process/upload/' . $row['profile_image'] . '" alt="User">';
            echo '<div class="comment-body">';
            echo '<span class="comment-user">' . $row['username'] . '</span>';
            echo '<p>' . $row['comment'] . '</p>';
            echo '</div></div>';
        }
    } else {
        echo "<p>No comments yet. Be the first to comment!</p>";
    }
}
?>
