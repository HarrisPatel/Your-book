<?php
include '../config.php';

session_start();

$post_id = $_GET['pid'];

$sql = "SELECT * FROM post INNER JOIN user_table ON post.user_id = user_table.id WHERE post_id = $post_id";
$result = mysqli_query($conn, $sql) or die("Query Failed");
$row = mysqli_fetch_assoc($result);



$file_name = $row['images'];


if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
    $error = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext_array = explode('.', $file_name);
    $file_ext = end($file_ext_array);
    $extentions = array("jpeg", "jpg", "png");

    // if ($file_size > 2097152) {
    //     $error[] = "File size must be under 2MB";
    // }

    if (empty($error)) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
    } else {
        print_r($error);
        die();
    }
}

$title = mysqli_real_escape_string($conn, $_POST['title']);
$des = mysqli_real_escape_string($conn, $_POST['description']);

$sql = "UPDATE post SET title = '{$title}', description = '{$des}', images = '{$file_name}' WHERE post_id = $post_id";

if (mysqli_query($conn, $sql)) {
    header("Location: ../pages/feed.php");
} else {
    echo "<div class='alert alert-danger'>Query Failed</div>";
}
