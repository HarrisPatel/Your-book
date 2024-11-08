<?php

include '../config.php';
session_start();

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);

    $sql =  "SELECT post.*, COUNT(likes.post_id) as likes_count 
    FROM post 
    LEFT JOIN likes ON post.post_id = likes.post_id
    WHERE post.title LIKE '%$search%' 
    GROUP BY post.post_id
    LIMIT 10";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="post">
                <a href="../pages/single-post.php?pid=' . $row['post_id'] . '" style="text-decoration: none; color : white">

                <div class="image">';
            if (strpos($row['images'], '.mp4') !== false) {
                echo '<svg class="video-svg" xmlns="http://www.w3.org/2000/svg" height="40" width="40" viewBox="0 0 512 512">
                        <path fill="#cdcbcb" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c7.6-4.2 16.8-4.1 24.3 .5l144 88c7.1 4.4 11.5 12.1 11.5 20.5s-4.4 16.1-11.5 20.5l-144 88c-7.4 4.5-16.7 4.7-24.3 .5s-12.3-12.2-12.3-20.9l0-176c0-8.7 4.7-16.7 12.3-20.9z" /> </svg> 
                        <video width="100%" height="auto" class="post-image" src="../process/upload/' . $row['images'] . '" type="video/mp4"></video>';
            } else {
                echo '<img src="../process/upload/' . $row['images'] . '" alt="Post Image" class="post-image">';
            }
            echo '
                    <div class="like-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="#ffffff" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z" />
                        </svg>
                        <p style="margin:2px">' . $row['likes_count'] . '</p>
                    </div>
                    
                </div>
                </a>
            </div>';
        }
    } else {
        echo '<p>No posts found.</p>';
    }
}
