<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id'])) {
    die('Unauthorized');
}

$my_id = $_SESSION['id'];
$user_id = $_POST['user_id'];


$sql = "DELETE FROM followers WHERE follower_id = $my_id AND user_id = $user_id AND id_status = 'accepted'";
mysqli_query($conn, $sql);
echo json_encode(['status' => false]);


exit;
