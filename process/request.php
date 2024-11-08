<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id'])) {
    die('Unauthorized');
}

$my_id = $_SESSION['id'];
$user_id = $_POST['user_id'];

$sql = "SELECT id_status FROM followers WHERE user_id = $user_id AND follower_id = $my_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (mysqli_num_rows($result) > 0) {
    if($row['id_status'] == 'requested'){
        $sql = "DELETE FROM followers WHERE user_id = $user_id AND follower_id = $my_id AND id_status = 'requested'";
        mysqli_query($conn, $sql);
        echo 'request';
    }
}else{
    $sql = "INSERT INTO followers (follower_id,user_id) VALUES ($my_id, $user_id)";
    mysqli_query($conn, $sql);
    echo 'requested';
}

?>