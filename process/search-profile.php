<?php

include '../config.php';

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);

    $sql = "SELECT * FROM user_table WHERE username LIKE '%$search%' LIMIT 10";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="profile-box">
                <a href="../pages/user-profile.php?uid='. $row['id'].'">
                    <div class="image">
                        <img src="../process/upload/' . $row['profile_image'] . '" alt="Profile Image">
                    </div>
                    <div class="text">
                        <h4>@' . htmlspecialchars($row['username']) . '</h4>
                    </div>
                </a>
            </div>';
        }
    } else {
        echo '<p>No profiles found.</p>';
    }
}
