<?php
include '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id'];
    $post_id = $_POST['post_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "INSERT INTO comments (post_id, user_id, comment, created_at) VALUES ($post_id, $user_id, '$comment', NOW())";
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
