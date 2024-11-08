<?php
include '../config.php';

$post_id = $_POST['post_id'];

$sql = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = $post_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo $row['like_count'];

exit;
