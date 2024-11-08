<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id'])) {
    die('Unauthorized');
}

$my_id = $_SESSION['id'];
$user_id = $_POST['user_id'];

$sql = "SELECT * FROM followers WHERE follower_id = $my_id AND user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $sql = "DELETE FROM followers WHERE follower_id = $my_id AND user_id = $user_id";
    mysqli_query($conn, $sql);
    echo json_encode(['status' => false]);
} else {
    $sql = "INSERT INTO followers (follower_id, user_id,id_status) VALUES ($my_id, $user_id,'accepted')";
    mysqli_query($conn, $sql);
    echo json_encode(['status' => true]);
}

exit;
?>
