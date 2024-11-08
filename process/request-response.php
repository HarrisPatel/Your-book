<?php
include '../config.php';
session_start();

$btn_type = $_POST['btn_type'];
$follower_id = $_POST['follower_id'];
$my_id = $_SESSION['id'];

if ($btn_type == 'accept') {
    $sql = "UPDATE followers SET id_status = 'accepted' WHERE follower_id = $follower_id AND user_id = $my_id";
    $result = mysqli_query($conn, $sql);
    echo 'accepted';
} else {
    $sql = "DELETE FROM followers WHERE follower_id = $follower_id AND user_id = $my_id AND id_status = 'requested'";
    $result = mysqli_query($conn, $sql);
    echo 'decline';
}
exit;
