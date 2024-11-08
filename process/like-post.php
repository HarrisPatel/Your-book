<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id'])) {
    die('Unauthorized');
}

$user_id = $_SESSION['id'];
$post_id = $_POST['post_id'];

$sql = "SELECT * FROM likes WHERE post_id = $post_id AND user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $sql = "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id";
    mysqli_query($conn, $sql);
    echo json_encode(['status' => 'disliked']);
} else {
    $sql = "INSERT INTO likes (post_id, user_id) VALUES ($post_id, $user_id)";
    mysqli_query($conn, $sql);
    echo json_encode(['status' => 'liked']);
}

exit;
?>
