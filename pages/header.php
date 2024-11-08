<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/feed.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/feed-modal.css">
    <link rel="stylesheet" href="../css/bottom-bar.css">
</head>
<style>
    .profile-options {
        position: relative;
    }
    .profile img{
        object-fit: cover;
    }
    .profile-btn {
        cursor: pointer;
        font-size: 20px;
    }
</style>

<body>

    <div class="header">
        <div class="header-box">
            <h1>YourBook</h1>

            <div class="post-options">
                <div class="dropdown">
                    <div class="profile options-btn" href="">
                        <img src="../process/upload/<?php echo $_SESSION['profile_image'] ?>"
                            alt="User Profile" class="profile-pic" style='border-radius : 100px; width : 40px;height:40px'>
                        <span class="user-name"><?php echo $_SESSION['username'] ?></span>
                    </div>
                    <div class="dropdown-content">
                        <?php
                            $id = $_SESSION['id'];
                            echo '<a href="user-profile.php?uid=' . $id . '">User Profile</a>';
                            echo '<a href="../process/logout.php">Logout</a>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>