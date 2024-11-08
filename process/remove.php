<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id'])) {
    die('Unauthorized');
}

$my_id = $_SESSION['id'];
$user_id = $_POST['user_id'];

$sql = "DELETE FROM followers WHERE follower_id = $user_id AND user_id = $my_id";
if(mysqli_query($conn, $sql)){
    header("Location: ../pages/followers.php?uid=".$my_id);

}else{

};

