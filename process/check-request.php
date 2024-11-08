<?php
include '../config.php';
session_start();

$user_id = $_POST['user_id'];
$my_id = $_SESSION['id'];

$sql = "SELECT * FROM followers WHERE user_id = $user_id AND follower_id = $my_id AND id_status = 'requested'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo 'requested';
} else {
    $sql1 = "SELECT * FROM followers WHERE user_id = $user_id AND follower_id = $my_id AND id_status = 'accepted'";
    $result1 = mysqli_query($conn, $sql1);
    if(mysqli_num_rows($result1) > 0) {
        echo 'unfollow';
    }else{
        echo 'request';
    }
}
exit;
