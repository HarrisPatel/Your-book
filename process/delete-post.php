<?php
include '../config.php';
session_start();

$post_id = $_GET['pid'];

$sql = "SELECT images FROM post WHERE post_id = $post_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $image_file = $row['images'];

    $sql_delete = "DELETE FROM post WHERE post_id = $post_id";
    if (mysqli_query($conn, $sql_delete)) {
        header('Location: ../pages/feed.php');
        exit();
    } else {
        die("Query Failed: " . mysqli_error($conn));
    }
} else {
    echo "Post not found.";
    exit();
}
?>
