<?php 

include '../config.php';
session_start();

if (isset($_FILES['create-image'])) {
    $errors = array();

    $file_name = $_FILES['create-image']['name'];
    $file_size = $_FILES['create-image']['size'];
    $file_tmp = $_FILES['create-image']['tmp_name'];
    $file_type = $_FILES['create-image']['type'];
    $file_ext_array = explode('.', $file_name); 
    $file_ext = end($file_ext_array);

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
        $_SESSION['image-error'] = "";
    } else {
        print_r($errors);
        die();
    }
}else{
    
}



$title = mysqli_real_escape_string($conn, $_POST['title']); 
$des = mysqli_real_escape_string($conn, $_POST['description']);
$user_id = $_SESSION['id'];
$upload_time = date('Y-m-d H:i:s');

$sql = "INSERT INTO post(title,description,user_id,images,upload_time) 
        VALUE('{$title}','{$des}',{$user_id},'{$file_name}','{$upload_time}')";

if (mysqli_multi_query($conn, $sql)) {
    header("Location: ../pages/feed.php");
}else{
    echo "<div class='alert alert-danger'>Query Failed: " . mysqli_error($conn) . "</div>";

}

?>