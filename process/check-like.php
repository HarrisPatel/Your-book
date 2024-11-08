<?php
include '../config.php';
session_start();

$post_id = $_POST['post_id'];
$user_id = $_SESSION['id'];

$sql = "SELECT * FROM likes WHERE post_id = $post_id AND user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['status' => true]); 
} else {
    echo json_encode(['status' => false]);
}
exit;
