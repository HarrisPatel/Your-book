<?php
include '../config.php';
session_start();

$user_id = $_POST['user_id'];
$my_id = $_SESSION['id'];

$sql = "SELECT * FROM followers WHERE follower_id = $my_id AND user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['status' => true]); 
} else {
    echo json_encode(['status' => false]);
}
exit;
